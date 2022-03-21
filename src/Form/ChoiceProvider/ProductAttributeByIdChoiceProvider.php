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

namespace Flavioski\Module\SalusPerAquam\Form\ChoiceProvider;

use Product;
use PrestaShop\PrestaShop\Core\Exception\CoreException;
use PrestaShop\PrestaShop\Core\Form\ConfigurableFormChoiceProviderInterface;
use PrestaShopException;
use Combination;
use Symfony\Component\OptionsResolver\OptionsResolver;


final class ProductAttributeByIdChoiceProvider implements ConfigurableFormChoiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getChoices(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $choices = [];

        $productId = $resolvedOptions['id_product'];
        try {
            //$productHasCombinations = (new ProductId($productId))::hasAttributes();

            //if (!$productHasCombinations) {
                return [];
            //}

            //$combinations = Product::getAttributesInformationsByProduct($productId);
            //$combinations = Product::getAttributeCombinations();

            //foreach ($combinations as $combination) {
            //    $choices[$combination['name']] = $combination['id_product_attribute'];
            //}
        } catch (PrestaShopException $e) {
            throw new CoreException(sprintf('An error occurred when getting states for product id "%s"', $productId));
        }

        return $choices;
    }

    /**
     * Configures array parameters and default values
     *
     * @param OptionsResolver $resolver
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['only_active' => false]);
        $resolver->setRequired('id_product');
        $resolver->setAllowedTypes('id_product', 'int');
        $resolver->setAllowedTypes('only_active', 'bool');
        $this->allowIdProductGreaterThanZero($resolver);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function allowIdProductGreaterThanZero(OptionsResolver $resolver)
    {
        $resolver->setAllowedValues('id_product', function ($value) {
            return 0 < $value;
        });
    }
}
