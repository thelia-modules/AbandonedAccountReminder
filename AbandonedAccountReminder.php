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

namespace AbandonedAccountReminder;

use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Core\Translation\Translator;
use Thelia\Install\Database;
use Thelia\Model\MessageQuery;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;
use Thelia\Model\Message;
use Thelia\Module\BaseModule;

class AbandonedAccountReminder extends BaseModule
{
    public const DOMAIN_NAME = 'abandonedaccountreminder';
    public const CONFIG_NAME_REMINDER_TIME = 'account_reminder_in_days';
    public const REMINDER_MESSAGE = 'abandoned-account-reminder-message';

    public function postActivation(ConnectionInterface $con = null): void
    {
        if (null === self::getConfigValue('is-initialized')) {
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/TheliaMain.sql"]);

            self::setConfigValue('is-initialized', 1);
        }

        if (null === self::getConfigValue(self::CONFIG_NAME_REMINDER_TIME)) {
            self::setConfigValue(self::CONFIG_NAME_REMINDER_TIME, 7);
        }

        if (null === MessageQuery::create()->findOneByName(self::REMINDER_MESSAGE)) {
            $message = new Message();
            $message
                ->setName(self::REMINDER_MESSAGE)
                ->setHtmlLayoutFileName('')
                ->setHtmlTemplateFileName('abandoned-account-reminder-mail.html')
                ->setTextLayoutFileName('')
                ->setTextTemplateFileName('abandoned-account-reminder-mail.txt');

            foreach (LangQuery::create()->find() as $language) {
                $locale = $language->getLocale();
                $message->setLocale($locale);

                $title = Translator::getInstance()->trans("Reminder: Your account has been created, place your first order!", [], self::DOMAIN_NAME, $locale);
                $message->setTitle($title)->setSubject($title);
            }

            $message->save();
        }
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([__DIR__.'/I18n/*'])
            ->autowire()
            ->autoconfigure();
    }
}
