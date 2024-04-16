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

use Currency;
use Flavioski\Module\SalusPerAquam\ConstraintValidator\Constraints\TreatmentProductAttributeRequired;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Configuration\TreatmentRateConstraint;
use Flavioski\Module\SalusPerAquam\Form\Type\ProductChoiceType;
use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentRateType extends TranslatorAwareType implements EventSubscriberInterface
{
    /**
     * @var ConfigurableFormChoiceProviderInterface
     */
    private $productAttributeChoiceProvider;

    /**
     * @var Currency
     */
    private $defaultCurrency;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TranslatorInterface $translator
     * @param array $locales
     * @param ConfigurableFormChoiceProviderInterface $productAttributeChoiceProvider
     * @param Currency $defaultCurrency
     * @param RouterInterface $router
     */
    public function __construct(
        TranslatorInterface $translator,
        array $locales,
        ConfigurableFormChoiceProviderInterface $productAttributeChoiceProvider,
        Currency $defaultCurrency,
        RouterInterface $router
    ) {
        parent::__construct($translator, $locales);
        $this->productAttributeChoiceProvider = $productAttributeChoiceProvider;
        $this->defaultCurrency = $defaultCurrency;
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'adaptSelf',
            FormEvents::PRE_SUBMIT => 'adaptSelf',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'label' => $this->trans('ID', 'Admin.Global'),
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('from_date', DateTimeType::class, [
                'label' => $this->trans('From', 'Admin.Global'),
                'attr' => [
                    'readonly' => true,
                ],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => 'true',
                'placeholder' => [
                    'year' => $this->trans('Year', 'Admin.Global'),
                    'month' => $this->trans('Month', 'Admin.Global'),
                    'day' => $this->trans('Day', 'Admin.Global'),
                    'hour' => $this->trans('Hour', 'Admin.Global'),
                    'minute' => $this->trans('Minute', 'Admin.Global'),
                    'second' => $this->trans('Second', 'Admin.Global'),
                ],
            ])
            ->add('to_date', DateTimeType::class, [
                'label' => $this->trans('To', 'Admin.Global'),
                'attr' => [
                    'readonly' => true,
                ],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => 'true',
                'placeholder' => [
                    'year' => $this->trans('Year', 'Admin.Global'),
                    'month' => $this->trans('Month', 'Admin.Global'),
                    'day' => $this->trans('Day', 'Admin.Global'),
                    'hour' => $this->trans('Hour', 'Admin.Global'),
                    'minute' => $this->trans('Minute', 'Admin.Global'),
                    'second' => $this->trans('Second', 'Admin.Global'),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => $this->trans('Description', 'Admin.Global'),
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('internal_id', TextType::class, [
                'label' => $this->trans('Internal Id', 'Modules.Salusperaquam.Admin'),
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('internal_id_rate', TextType::class, [
                'label' => $this->trans('Internal Id Rate', 'Modules.Salusperaquam.Admin'),
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => $this->trans('Price', 'Admin.Global'),
                'help' => 'Price treatment (e.g. 12.45).',
                'scale' => 2,
                'currency' => $this->defaultCurrency->iso_code,
                'attr' => [
                    'readonly' => true,
                    'min' => TreatmentRateConstraint::MIN_PRICE_VALUE,
                    'max' => TreatmentRateConstraint::MAX_PRICE_VALUE,
                    'step' => TreatmentRateConstraint::STEP_PRICE_VALUE,
                ],
            ])
            ->add('id_product', ProductChoiceType::class, [
                'label' => $this->trans('Product', 'Admin.Global'),
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->trans(
                            'This field cannot be empty.', 'Admin.Notifications.Error'
                        ),
                    ]),
                ],
                'attr' => [
                    'data-combinations-url' => $this->router->generate('flavioski_salusperaquam_product_combinations'),
                ],
            ])
        ;

        // The form type acts as its own listener to dynamize some field options
        $builder->addEventSubscriber($this);
    }

    /**
     * @param FormEvent $event
     */
    public function adaptSelf(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();

        $productId = 0 !== $data['id_product'] ? $data['id_product'] : 0;
        $productAttributeChoices = $this->productAttributeChoiceProvider->getChoices(['id_product' => (int) $productId]);

        $showProductAttributes = !empty($productAttributeChoices);

        $form
            ->add('id_product_attribute', ChoiceType::class, [
                'label' => $this->trans('Combination', 'Admin.Global'),
                'required' => true,
                'choices' => $productAttributeChoices,
                'constraints' => [
                    new TreatmentProductAttributeRequired([
                        'id_product' => $productId,
                    ]),
                ],
                'row_attr' => [
                    'class' => 'js-treatment-product-attribute-select',
                ],
                'attr' => [
                    'visible' => $showProductAttributes,
                ],
            ])
            ->add('active', SwitchType::class, [
                'label' => $this->trans('Status', 'Admin.Global'),
                'help' => $this->trans('This treatment rate is active?', 'Modules.Salusperaquam.Admin'),
                'required' => true,
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
