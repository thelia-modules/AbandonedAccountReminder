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

use AbandonedAccountReminder\AbandonedAccountReminder;
use AbandonedAccountReminder\Form\ConfigurationForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Thelia\Controller\Admin\AdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Template\ParserContext;
use Thelia\Form\Exception\FormValidationException;


#[Route('/admin/module/AbandonedAccountReminder', name: 'abandoned_account_reminder_admin')]
class ConfigurationController extends AdminController
{
    #[Route('/configure', name: '_configure', methods: ['POST'])]
    public function save(ParserContext $parserContext): RedirectResponse|Response|null
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, 'AbandonedAccountReminder', AccessManager::UPDATE)) {
            return $response;
        }

        $configurationForm = $this->createForm(ConfigurationForm::getName());

        try {
            $data = $this->validateForm($configurationForm)->getData();

            AbandonedAccountReminder::setConfigValue(AbandonedAccountReminder::CONFIG_NAME_REMINDER_TIME, $data[AbandonedAccountReminder::CONFIG_NAME_REMINDER_TIME] ?? '');

            return $this->generateSuccessRedirect($configurationForm);
        } catch (FormValidationException $ex) {
            $errorMessage = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $errorMessage = $ex->getMessage();
        }

        $configurationForm->setErrorMessage($errorMessage);

        $parserContext
            ->addForm($configurationForm)
            ->setGeneralError($errorMessage);

        return $this->generateErrorRedirect($configurationForm);
    }
}
