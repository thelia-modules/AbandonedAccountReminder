<?php

namespace AbandonedAccountReminder\Hook;

use AbandonedAccountReminder\AbandonedAccountReminder;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class HookManager extends BaseHook
{
    public function onModuleConfigure(HookRenderEvent $event): void
    {
        $event->add(
            $this->render('module-configuration.html', [
                'account_reminder_in_days' => AbandonedAccountReminder::getConfigValue(AbandonedAccountReminder::CONFIG_NAME_REMINDER_TIME),
                'url_tracking_arguments' => AbandonedAccountReminder::getConfigValue(AbandonedAccountReminder::CONFIG_NAME_URL_TRACKING_ARGUMENTS)
            ])
        );
    }
}
