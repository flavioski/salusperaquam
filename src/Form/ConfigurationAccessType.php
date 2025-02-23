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

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationAccessType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('configuration_login_url', TextType::class, [
                'label' => $this->trans(
                    'Login > URL',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-login-option',
                ],
            ])
            ->add('configuration_login_biding', TextType::class, [
                'label' => $this->trans(
                    'Login > Biding',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-login-option',
                ],
            ])
            ->add('configuration_login_resource', TextType::class, [
                'label' => $this->trans(
                    'Login > Resource',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-login-option',
                ],
            ])
            ->add('configuration_user_url', TextType::class, [
                'label' => $this->trans(
                    'User > URL',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-user-option',
                ],
            ])
            ->add('configuration_user_biding', TextType::class, [
                'label' => $this->trans(
                    'User > Biding',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-user-option',
                ],
            ])
            ->add('configuration_user_resource', TextType::class, [
                'label' => $this->trans(
                    'User > Resource',
                    'Modules.Salusperaquam.Admin'
                ),
                'required' => true,
                'empty_data' => '',
                'row_attr' => [
                    'class' => 'configuration-user-option',
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
        return 'configuration_access_general_block';
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
