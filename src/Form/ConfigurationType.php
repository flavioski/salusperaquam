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

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('test', SwitchType::class, [
                'label' => $this->trans(
                    'Test mode',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
            ])
            ->add('test_url', TextType::class, [
                'label' => $this->trans(
                    'URL',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-test-option',
                ],
            ])
            ->add('test_username', TextType::class, [
                'label' => $this->trans(
                    'Username',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-test-option',
                ],
            ])
            ->add('test_password', TextType::class, [
                'label' => $this->trans(
                    'Password',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-test-option',
                ],
            ])
            ->add('test_resource_get_treatment', TextType::class, [
                'label' => $this->trans(
                    'Resource GetTreatment',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-test-option',
                ],
            ])
            ->add('test_resource_add_sale', TextType::class, [
                'label' => $this->trans(
                    'Resource AddSale',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-test-option',
                ],
            ])
            ->add('production', SwitchType::class, [
                'label' => $this->trans(
                    'Production mode',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
            ])
            ->add('production_url', TextType::class, [
                'label' => $this->trans(
                    'URL',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-production-option',
                ],
            ])
            ->add('production_username', TextType::class, [
                'label' => $this->trans(
                    'Username',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-production-option',
                ],
            ])
            ->add('production_password', TextType::class, [
                'label' => $this->trans(
                    'Password',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-production-option',
                ],
            ])
            ->add('production_resource_get_treatment', TextType::class, [
                'label' => $this->trans(
                    'Resource GetTreatment',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-production-option',
                ],
            ])
            ->add('production_resource_add_sale', TextType::class, [
                'label' => $this->trans(
                    'Resource AddSale',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => false,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-production-option',
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'configuration_general_block';
    }

    /**
     * If some value is different from default-value, option message should be completed with specific reason.
     *
     * @return array
     */
    public function getErrorsMessages()
    {
        return [
            'http' => $this->trans('http (outdated)', 'Modules.Salusperaquam.Admin'),
        ];
    }
}
