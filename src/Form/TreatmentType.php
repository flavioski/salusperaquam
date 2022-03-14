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

use Flavioski\Module\SalusPerAquam\Form\DataTransformer\CentToEuroTransformer;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
                'label' => 'Name treatment',
                'help' => 'Name treatment (e.g. Massage).',
                'translation_domain' => 'Modules.Salusperaquam.Admin',
                'constraints' => [
                    new Length([
                        'max' => 128,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => 128]
                        ),
                    ]),
                    new NotBlank(),
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'Code treatment',
                'help' => 'Code treatment (e.g. Massage-12345).',
                'translation_domain' => 'Modules.Salusperaquam.Admin',
                'constraints' => [
                    new Length([
                        'max' => 128,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => 128]
                        ),
                    ]),
                    new NotBlank(),
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price treatment',
                'help' => 'Price treatment (e.g. 12.45).',
                'translation_domain' => 'Modules.Salusperaquam.Admin',
                'scale' => 2,
                'currency' => null,
                'attr' => [
                    'min' => '0.00',
                    'max' => '1000.00',
                    'step' => '0.01'
                ]
            ])
        ;

        $builder->get('price')
            ->addModelTransformer(new CentToEuroTransformer());
    }
}
