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

use Flavioski\Module\SalusPerAquam\Repository\TreatmentRateRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class TreatmentRateFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var TreatmentRateRepository
     */
    private $treatmentRateRepository;

    /**
     * @param TreatmentRateRepository $treatmentRateRepository
     */
    public function __constructor(
        TreatmentRateRepository $treatmentRateRepository
    ) {
        $this->treatmentRateRepository = $treatmentRateRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($treatmentRateId)
    {
        $treatmentRate = $this->treatmentRateRepository->findOneById($treatmentRateId);

        return [
            'id_product' => $treatmentRate->getProductId(),
            'id_product_attribute' => $treatmentRate->getProductAttributeId(),
            'to_date' => $treatmentRate->getToDate(),
            'to_time' => $treatmentRate->getToTime(),
            'from_date' => $treatmentRate->getFromDate(),
            'from_time' => $treatmentRate->getFromTime(),
            'description' => $treatmentRate->getDescription(),
            'weekdays' => $treatmentRate->getWeekdays(),
            'weekend' => $treatmentRate->getWeekend(),
            'internal_id' => $treatmentRate->getInternalId(),
            'internal_id_rate' => $treatmentRate->getInternalIdRate(),
            'price' => $treatmentRate->getPrice(),
            'installment_payment_plan' => $treatmentRate->isInstallmentPaymentPlan(),
            'discount' => $treatmentRate->getDiscount(),
            'active' => $treatmentRate->isActive(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData()
    {
        return [
            'id_product' => 0,
            'id_product_attribute' => null,
            'to_date' => new \DateTime('now'),
            'from_date' => new \DateTime('now'),
            'description' => '',
            'weekdays' => '',
            'weekend' => '',
            'internal_id' => '',
            'internal_id_rate' => '',
            'price' => 0,
            'installment_payment_plan' => false,
            'discount' => 0,
            'active' => false,
        ];
    }
}
