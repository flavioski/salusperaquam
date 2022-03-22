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

namespace Flavioski\Module\SalusPerAquam\Form\Type;

use PrestaShop\PrestaShop\Core\Form\FormChoiceAttributeProviderInterface;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductChoiceType extends AbstractType
{
    /**
     * @var FormChoiceProviderInterface
     */
    private $productsChoiceProvider;

    /**
     * @var FormChoiceAttributeProviderInterface
     */
    private $productsAttrChoicesProvider;

    /**
     * @var array
     */
    private $productsAttr = [];

    /**
     * @param FormChoiceProviderInterface $productsChoiceProvider
     * @param FormChoiceAttributeProviderInterface $productsAttrChoicesProvider
     */
    public function __construct(FormChoiceProviderInterface $productsChoiceProvider, FormChoiceAttributeProviderInterface $productsAttrChoicesProvider)
    {
        $this->productsChoiceProvider = $productsChoiceProvider;
        $this->productsAttrChoicesProvider = $productsAttrChoicesProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->productsAttr = $this->productsAttrChoicesProvider->getChoicesAttributes();

        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array_merge(
            ['--' => ''],
            $this->productsChoiceProvider->getChoices()
        );

        $resolver->setDefaults([
            'choices' => $choices,
            'choice_attr' => [$this, 'getChoiceAttr'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
