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
 * @author    Flavio Pellizzer
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Database;

use Doctrine\ORM\EntityManagerInterface;
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\Entity\TreatmentLang;
use Flavioski\Module\SalusPerAquam\Entity\TreatmentRate;
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository;
use PrestaShopBundle\Entity\Repository\LangRepository;

class TreatmentGenerator
{
    /**
     * @var TreatmentRepository
     */
    private $treatmentRepository;

    /**
     * @var LangRepository
     */
    private $langRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param TreatmentRepository $treatmentRepository
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TreatmentRepository $treatmentRepository,
        LangRepository $langRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->treatmentRepository = $treatmentRepository;
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
    }

    public function generateTreatments()
    {
        $this->removeAllTreatments();
        $this->insertTreatments();
    }

    private function removeAllTreatments()
    {
        $treatments = $this->treatmentRepository->findAll();
        foreach ($treatments as $treatment) {
            $this->entityManager->remove($treatment);
        }
        $this->entityManager->flush();
    }

    private function insertTreatments()
    {
        $languages = $this->langRepository->findAll();

        $treatmentsDataFile = __DIR__ . '/../../Resources/data/treatments.json';
        $treatmentsData = json_decode(file_get_contents($treatmentsDataFile), true);

        foreach ($treatmentsData as $treatmentData) {
            $treatment = new Treatment();
            $treatment->setName($treatmentData['name']);
            $treatment->setCode($treatmentData['code']);
            $treatment->setSingleSale((bool) $treatmentData['single_sale']);
            $treatment->setType((int) $treatmentData['type']);
            $treatment->setPackageType((int) $treatmentData['package_type']);
            $treatment->setActive((bool) $treatmentData['active']);
            /** @var \PrestaShopBundle\Entity\Lang $language */
            foreach ($languages as $language) {
                $treatmentLang = new TreatmentLang();
                $treatmentLang->setLang($language);
                if (isset($treatmentData['content'][$language->getIsoCode()])) {
                    $treatmentLang->setContent($treatmentData['content'][$language->getIsoCode()]);
                } else {
                    $treatmentLang->setContent($treatmentData['content']['en']);
                }
                $treatment->addTreatmentLang($treatmentLang);
            }

            foreach ($treatmentData['rates'] as $rate) {
                $treatmentRate = new TreatmentRate();
                $fromDate = new \DateTime($rate['from_date'] . ' ' . $rate['from_time']);
                $toDate = new \DateTime($rate['to_date'] . ' ' . $rate['to_time']);
                $treatmentRate->setFromDate($fromDate);
                $treatmentRate->setToDate($toDate);
                $treatmentRate->setDescription($rate['description']);
                $treatmentRate->setProductId((int) $rate['product_id']);
                $treatmentRate->setProductAttributeId((int) $rate['product_attribute_id']);
                $treatmentRate->setPrice((float) $rate['price']);
                $treatmentRate->setActive((bool) $rate['active']);
                $treatment->addTreatmentRate($treatmentRate);
            }

            $this->entityManager->persist($treatment);
        }

        $this->entityManager->flush();
    }
}
