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

use Doctrine\ORM\EntityManagerInterface;
use Flavioski\Module\SalusPerAquam\Entity\TreatmentRate;
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRateRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;

class TreatmentRateFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var TreatmentRateRepository
     */
    private $treatmentRateRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param TreatmentRateRepository $treatmentRateRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TreatmentRateRepository $treatmentRateRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->treatmentRateRepository = $treatmentRateRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $treatmentRate = new TreatmentRate();
        $treatmentRate->setFromDate($data['from_date']);
        $treatmentRate->setToDate($data['to_date']);
        $treatmentRate->setInternalId($data['internal_id']);
        $treatmentRate->setInternalIdRate($data['internal_id_rate']);
        $treatmentRate->setPrice($data['price']);
        $treatmentRate->setActive($data['active']);
        $treatmentRate->setProductId((int) $data['id_product']);
        $treatmentRate->setProductAttributeId($data['id_product_attribute']);

        $this->entityManager->persist($treatmentRate);
        $this->entityManager->flush();

        return $treatmentRate->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        if (isset($data['treatment_rates'])) {
            foreach ($data['treatment_rates'] as $treatmentRate) {
                $this->updateSingleTreatmentRate($treatmentRate['id'], $treatmentRate);
            }
        }
    }

    private function updateSingleTreatmentRate($id, $data)
    {
        $treatmentRate = $this->treatmentRateRepository->findOneById($id);
        $treatmentRate->setFromDate($data['from_date']);
        //$treatmentRate->setFromTime($data['from_time']);
        $treatmentRate->setToDate($data['to_date']);
        //$treatmentRate->setToTime($data['to_time']);
        $treatmentRate->setDescription($data['description']);
        $treatmentRate->setInternalId($data['internal_id']);
        $treatmentRate->setInternalIdRate($data['internal_id_rate']);
        $treatmentRate->setPrice($data['price']);
        $treatmentRate->setActive($data['active']);
        $treatmentRate->setProductId((int) $data['id_product']);
        $treatmentRate->setProductAttributeId($data['id_product_attribute']);

        $this->entityManager->flush();

        return $treatmentRate->getId();
    }
}
