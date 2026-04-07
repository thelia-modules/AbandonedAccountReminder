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

namespace AbandonedAccountReminder\Form;

use AbandonedAccountReminder\AbandonedAccountReminder;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ConfigurationForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->formBuilder
            ->add(
                AbandonedAccountReminder::CONFIG_NAME_REMINDER_TIME,
                NumberType::class,
                [
                    "required" => true,
                    "constraints" => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => 0])
                    ],
                    "label" => $this->translator->trans('Time in days before sending the reminder', [], AbandonedAccountReminder::DOMAIN_NAME),
                    'label_attr'  => [
                        'help' => $this->translator->trans(
                            'Number of days to wait after account creation before sending the email.',
                            [],
                            AbandonedAccountReminder::DOMAIN_NAME
                        ),
                    ],
                ]
            )
        ;
    }

    public static function getName(): string {
        return 'abandoned_account_reminder_configuration_form';
    }
}
