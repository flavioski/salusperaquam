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

use PrestaShop\PrestaShop\Core\Form\FormChoiceAttributeProviderInterface;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;
use Product;

final class ProductByIdChoiceProvider implements FormChoiceProviderInterface, FormChoiceAttributeProviderInterface
{
    /**
     * @var int
     */
    private $langId;

    /**
     * @var array
     */
    private $products;

    /**
     * @param int $langId
     */
    public function __construct(
        $langId
    ) {
        $this->langId = $langId;
    }

    /**
     * Get currency choices.
     *
     * @return array
     */
    public function getChoices()
    {
        $products = $this->getProducts();
        $choices = [];

        foreach ($products as $product) {
            $choices[$product['name']] = $product['id_product'];
        }

        return $choices;
    }

    /**
     * @return array
     */
    public function getChoicesAttributes()
    {
        $choicesAttributes = [];

        return $choicesAttributes;
    }

    /**
     * @return array
     */
    private function getProducts()
    {
        if (null === $this->products) {
            $this->products = Product::getProducts($this->langId, 0, 20000, 'id_product', 'ASC');
        }

        return $this->products;
    }
}
