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

namespace AbandonedAccountReminder\Controller;

use AbandonedAccountReminder\Events\AbandonedAccountEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Event\DefaultActionEvent;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Log\Tlog;

class ExamineAccountController extends BaseFrontController {

    #[Route('/admin/AbandonedAccountReminder/cron', name: 'abandoned_account_reminder_cron')]
    public function examine(EventDispatcherInterface $dispatcher): Response
    {
        try {
            $dispatcher->dispatch(new DefaultActionEvent(), AbandonedAccountEvent::EXAMINE_ACCOUNTS_EVENT);
        } catch (\Exception $ex) {
            Tlog::getInstance()->error("Error, can't examine abandoned accounts :" . $ex->getMessage());
            Tlog::getInstance()->error($ex);
            throw $ex;
        }

        return new Response("Abandoned accounts check finished.");
    }
}
