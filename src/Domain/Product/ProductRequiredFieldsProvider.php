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

namespace Flavioski\Module\SalusPerAquam\Domain\Product;

use Flavioski\Module\SalusPerAquam\Domain\Product\Exception\ProductNotFoundException;
use Flavioski\Module\SalusPerAquam\Domain\Product\ValueObject\ProductId;
use PrestaShop\PrestaShop\Adapter\Attribute\Repository\AttributeRepository;
use PrestaShop\PrestaShop\Adapter\Product\ProductDataProvider;
use PrestaShop\PrestaShop\Adapter\Product\Repository\ProductRepository;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId as PrestaProductId;


class ProductRequiredFieldsProvider implements ProductRequiredFieldsProviderInterface
{
    /**
     * @var int
     */
    private $langId;

    /**
     * @var ProductDataProvider
     */
    private $productDataProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @param int $langId
     * @param ProductDataProvider $productDataProvider
     * @param ProductRepository $productRepository
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(
        int $langId,
        ProductDataProvider $productDataProvider,
        ProductRepository $productRepository,
        AttributeRepository $attributeRepository
    ) {
        $this->langId = $langId;
        $this->productDataProvider = $productDataProvider;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ProductNotFoundException
     */
    public function isCombinationsRequired(ProductId $productId): bool
    {
        $prestaProductId = new PrestaProductId($productId->getValue());
        $productAttributes = $this->attributeRepository->getProductAttributesIds($prestaProductId);

        return boolval(count($productAttributes));
    }
}
