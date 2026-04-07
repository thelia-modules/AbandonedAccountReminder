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

namespace AbandonedAccountReminder\Command;

use AbandonedAccountReminder\Events\AbandonedAccountEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;
use Thelia\Core\Event\DefaultActionEvent;

#[AsCommand(
    name: 'examine-abandoned-accounts',
    description: 'Examine abandoned accounts and send a reminder if needed.'
)]
class ExamineAbandonedAccount extends ContainerAwareCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initRequest();

        try {
            $this->getDispatcher()->dispatch(new DefaultActionEvent(), AbandonedAccountEvent::EXAMINE_ACCOUNTS_EVENT);
        } catch (\Exception $ex) {
            $output->writeln("<error>".$ex->getMessage()."</error>");
            $output->writeln("<error>".$ex->getTraceAsString()."</error>");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
