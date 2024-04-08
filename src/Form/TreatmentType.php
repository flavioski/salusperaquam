<?php
/**
 * Salus per Aquam
 * Copyright since 2021 Flavio Pellizzer and Contributors
 * <Flavio Pellizzer> Property
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to flappio.pelliccia@gmail.com so we can send you a copy immediately.
 *
 * @author    Flavio Pellizzer <flappio.pelliccia@gmail.com>
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Form;

use Flavioski\Module\SalusPerAquam\Domain\Treatment\Configuration\TreatmentConstraint;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use PrestaShop\PrestaShop\Core\ConstraintValidator\TypedRegexValidator;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->trans('Name', 'Admin.Global'),
                'help' => $this->trans(
                        'Invalid characters:',
                        'Admin.Notifications.Info'
                    ) . ' ' . TypedRegexValidator::NAME_CHARS,
                'attr' => [
                    'readonly' => true,
                ],
                'constraints' => [
                    new CleanHtml(),
                    new TypedRegex([
                        'type' => TypedRegex::TYPE_NAME,
                    ]),
                    new Length([
                        'max' => TreatmentConstraint::MAX_TREATMENT_NAME_LENGTH,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => TreatmentConstraint::MAX_TREATMENT_NAME_LENGTH]
                        ),
                    ]),
                    new NotBlank(),
                ],
            ])
            ->add('content', TranslatableType::class, [
                'label' => $this->trans('Content', 'Admin.Global'),
                'help' => 'Treatment content (e.g. All for one, one for all).',
                'constraints' => [
                    new DefaultLanguage([
                        'message' => $this->trans(
                            'The field %field_name% is required at least in your default language.',
                            'Admin.Notifications.Error',
                            [
                                '%field_name%' => sprintf(
                                    '"%s"',
                                    $this->trans('Content', 'Modules.Salusperaquam.Admin')
                                ),
                            ]
                        ),
                    ]),
                ],
            ])
            ->add('code', TextType::class, [
                'label' => $this->trans('Code', 'Admin.Global'),
                'help' => 'Code treatment (e.g. Massage-12345).',
                'attr' => [
                    'readonly' => true,
                ],
                'constraints' => [
                    new Length([
                        'max' => TreatmentConstraint::MAX_CODE_LENGTH,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => TreatmentConstraint::MAX_CODE_LENGTH]
                        ),
                    ]),
                    new NotBlank(),
                ],
            ])
            ->add('active', SwitchType::class, [
                'label' => $this->trans('Status', 'Admin.Global'),
                'help' => 'Treatment is active?',
                'required' => true,
            ])
            ->add('treatment_rates', CollectionType::class, [
                'label' => $this->trans('Rates', 'Modules.Salusperaquam.Admin'),
                'label_tag_name' => 'h4',
                'entry_type' => TreatmentRateType::class,
                'prototype' => true,
                'prototype_name' => '__TREATMENT_RATE_INDEX__',
                'attr' => [
                    'class' => 'treatment-rates-collection',
                ],
                'row_attr' => [
                    'class' => 'js-treatment-product-attribute-select',
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'Modules.Salusperaquam.Admin',
        ]);
    }
}
