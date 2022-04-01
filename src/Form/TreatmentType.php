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
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Configuration\TreatmentConstraint;
use Flavioski\Module\SalusPerAquam\Form\Type\ProductChoiceType;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\TypedRegex;
use PrestaShop\PrestaShop\Core\ConstraintValidator\TypedRegexValidator;
use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TreatmentType extends TranslatorAwareType
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $productId = 0 !== $data['id_product'] ? $data['id_product'] : 0;
        $productAttributeChoices = $this->productAttributeChoiceProvider->getChoices(['id_product' => $productId]);

        $showProductAttributes = !empty($productAttributeChoices);

        $builder
            ->add('name', TextType::class, [
                'label' => $this->trans('Name', 'Admin.Global'),
                'help' => $this->trans(
                        'Invalid characters:',
                        'Admin.Notifications.Info'
                    ) . ' ' . TypedRegexValidator::NAME_CHARS,
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
            ->add('price', MoneyType::class, [
                'label' => $this->trans('Price', 'Admin.Global'),
                'help' => 'Price treatment (e.g. 12.45).',
                'scale' => 2,
                'currency' => $this->defaultCurrency->iso_code,
                'attr' => [
                    'min' => TreatmentConstraint::MIN_PRICE_VALUE,
                    'max' => TreatmentConstraint::MAX_PRICE_VALUE,
                    'step' => TreatmentConstraint::STEP_PRICE_VALUE,
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
                'help' => 'Treatment is active?',
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
