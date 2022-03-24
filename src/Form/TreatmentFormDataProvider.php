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

use Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class TreatmentFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var TreatmentRepository
     */
    private $treatmentRepository;

    /**
     * @param TreatmentRepository $treatmentRepository
     */
    public function __construct(
        TreatmentRepository $treatmentRepository
    ) {
        $this->treatmentRepository = $treatmentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($treatmentId)
    {
        $treatment = $this->treatmentRepository->findOneById($treatmentId);

        $treatmentData = [
            'name' => $treatment->getName(),
            'code' => $treatment->getCode(),
            'price' => $treatment->getPrice(),
            'id_product' => $treatment->getProductId(),
            'id_product_attribute' => $treatment->getProductAttributeId(),
            'active' => $treatment->isActive(),
        ];

        return $treatmentData;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData()
    {
        return [
            'name' => '',
            'code' => '',
            'price' => 0,
            'id_product' => 0,
            'id_product_attribute' => null,
            'active' => false,
        ];
    }
}
