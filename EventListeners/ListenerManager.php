<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace AbandonedAccountReminder\EventListeners;

use AbandonedAccountReminder\Events\AbandonedAccountEvent;
use AbandonedAccountReminder\Model\AbandonedAccount;
use AbandonedAccountReminder\Model\AbandonedAccountQuery;
use AbandonedAccountReminder\AbandonedAccountReminder;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\ActionEvent;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Log\Tlog;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\ConfigQuery;
use Thelia\Model\CustomerQuery;
use Thelia\Model\OrderQuery;

class ListenerManager implements EventSubscriberInterface
{
    public function __construct(protected MailerFactory $mailer)
    {}

    public function cron(ActionEvent $event): void
    {
        Tlog::getInstance()->notice("Examine abandoned accounts");

        $this->identifyNewAbandonedAccounts();

        $this->sendReminder(
            AbandonedAccountReminder::CONFIG_NAME_REMINDER_TIME,
            AbandonedAccount::STATUS_NOT_SENT,
            AbandonedAccountReminder::REMINDER_MESSAGE,
            AbandonedAccount::STATUS_SENT
        );
    }

    protected function identifyNewAbandonedAccounts(): void
    {
        $oneMonthAgo = (new \DateTime())->modify('-1 month');

        $customers = CustomerQuery::create()
            ->filterByCreatedAt($oneMonthAgo, Criteria::GREATER_EQUAL)
            ->useOrderQuery(null, Criteria::LEFT_JOIN)
                ->filterById(null, Criteria::ISNULL)
            ->endUse()
            ->find();

        foreach ($customers as $customer) {
            $exists = AbandonedAccountQuery::create()
                ->filterByCustomerId($customer->getId())
                ->exists();

            if (!$exists) {
                (new AbandonedAccount())
                    ->setCustomerId($customer->getId())
                    ->setCustomerEmail($customer->getEmail())
                    ->setLocale($customer->getCustomerLang()->getLocale())
                    ->setLastUpdate($customer->getCreatedAt())
                    ->save();
            }
        }
    }

    protected function sendReminder(string $delayVar, int $statusFilter, string $messageCode, int $newState): void
    {
        $delayDate = (new \DateTime())->sub(new \DateInterval('P' . (int) AbandonedAccountReminder::getConfigValue($delayVar) . 'D'));

        $abandonedAccounts = AbandonedAccountQuery::create()
            ->filterByStatus($statusFilter)
            ->filterByLastUpdate($delayDate, Criteria::LESS_THAN)
            ->find();

        foreach ($abandonedAccounts as $abandonedAccount) {
            $orderCount = OrderQuery::create()->filterByCustomerId($abandonedAccount->getCustomerId())->count();
            if ($orderCount > 0) {
                $abandonedAccount->delete();
                continue;
            }

            try {
                $customer = CustomerQuery::create()->findPk($abandonedAccount->getCustomerId());
                $this->mailer->sendEmailMessage(
                    $messageCode,
                    [ConfigQuery::getStoreEmail() => ConfigQuery::getStoreName()],
                    [$abandonedAccount->getCustomerEmail() => $abandonedAccount->getCustomerEmail()],
                    [
                        'customer_id' => $abandonedAccount->getCustomerId(),
                        'customer_firstname' => $customer ? $customer->getFirstname() : '',
                        'url_tracking_arguments' => AbandonedAccountReminder::getConfigValue(AbandonedAccountReminder::CONFIG_NAME_URL_TRACKING_ARGUMENTS)
                    ],
                    $abandonedAccount->getLocale()
                );
                Tlog::getInstance()->notice("Sending account reminder to customer " . $abandonedAccount->getCustomerEmail());
            } catch (\Exception $ex) {
                Tlog::getInstance()->error("Failed to send account reminder to customer " . $abandonedAccount->getCustomerEmail() . ". Reason:".$ex->getMessage());
            }

            $abandonedAccount
                ->setStatus($newState)
                ->setLastUpdate(new \DateTime())
                ->save();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AbandonedAccountEvent::EXAMINE_ACCOUNTS_EVENT => [ 'cron', 100 ]
        ];
    }
}