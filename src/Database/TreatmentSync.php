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
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRateRepository;
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository;
use Flavioski\Module\SalusPerAquam\WebService\Exception\WebServiceException;
use Flavioski\Module\SalusPerAquam\WebService\GetTreatment;
use PrestaShopBundle\Entity\Repository\LangRepository;

class TreatmentSync
{
    /**
     * @var TreatmentRepository
     */
    private $treatmentRepository;

    /**
     * @var TreatmentRateRepository
     */
    private $treatmentRateRepository;

    /**
     * @var LangRepository
     */
    private $langRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var GetTreatment
     */
    private $getTreatment;

    /**
     * @param TreatmentRepository $treatmentRepository
     * @param TreatmentRateRepository $treatmentRateRepository
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     * @param GetTreatment $getTreatment
     */
    public function __construct(
        TreatmentRepository $treatmentRepository,
        TreatmentRateRepository $treatmentRateRepository,
        LangRepository $langRepository,
        EntityManagerInterface $entityManager,
        GetTreatment $getTreatment
    ) {
        $this->treatmentRepository = $treatmentRepository;
        $this->treatmentRateRepository = $treatmentRateRepository;
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
        $this->getTreatment = $getTreatment;
    }

    /**
     * @return WebServiceException|void
     *
     * @throws \SoapFault
     */
    public function syncTreatments()
    {
        $webServiceResponse = $this->getTreatment->Request();

        if (isset($webServiceResponse->Success) && is_object($webServiceResponse->Result)) {
            $this->updateOrInsertTreatments($webServiceResponse->Result);
        } else {
            return new WebServiceException(sprintf(
                'Invalid call web service: "%s"',
                $webServiceResponse->getMessage()
            ), $webServiceResponse->getCode()
            );
        }
    }

    /**
     * @param object $treatmentsData
     *
     * @return void
     */
    private function updateOrInsertTreatments(object $treatmentsData)
    {
        foreach ($treatmentsData->Map as $key => $treatmentsDatum) {
            foreach ($treatmentsDatum->item as $item) {
                if ($item->key == 'Code') {
                    $findedByCode = $this->treatmentRepository->findOneByCode($item->value);

                    if ($findedByCode) {
                        $treatment = $this->treatmentRepository->findOneBy(['code' => $item->value]);
                        $this->updateTreatment($treatment, $treatmentsDatum);
                    } else {
                        $this->insertTreatment($treatmentsDatum);
                    }
                }
            }
        }
    }

    /**
     * @param Treatment $treatment
     * @param object $treatmentsDatum
     *
     * @return void
     */
    private function updateTreatment(Treatment $treatment, object $treatmentsDatum)
    {
        $languages = $this->langRepository->findAll();

        foreach ($treatmentsDatum->item as $item) {
            if ($item->key == 'Name') {
                $treatment->setName($item->value);

                foreach ($languages as $language) {
                    $treatmentLang = $treatment->getTreatmentLangByLangId($language->getId());
                    $treatmentLang->setContent($item->value);
                }
            }

            if ($item->key == 'Price') {
                $treatment->setPrice(floatval($item->value));
            }

            if ($item->key == 'Rate' && is_object($item->value)) {
                $this->updateOrInsertTreatmentRates($treatment, $item->value);
            }
        }

        $this->entityManager->persist($treatment);
        $this->entityManager->flush();
    }

    /**
     * @param object $treatmentsDatum
     *
     * @return void
     */
    private function insertTreatment(object $treatmentsDatum)
    {
        $languages = $this->langRepository->findAll();

        $treatment = new Treatment();

        $treatment->setActive(false);
        $treatment->setProductId(0);
        $treatment->setProductAttributeId(0);

        foreach ($treatmentsDatum->item as $item) {
            if ($item->key == 'Name') {
                $treatment->setName($item->value);

                foreach ($languages as $language) {
                    $treatmentLang = new TreatmentLang();
                    $treatmentLang->setLang($language);
                    $treatmentLang->setContent($item->value);
                    $treatment->addTreatmentLang($treatmentLang);
                }
            }

            if ($item->key == 'Code') {
                $treatment->setCode($item->value);
            }

            if ($item->key == 'Price') {
                $treatment->setPrice(floatval($item->value));
            }

            if ($item->key == 'Rate' && is_object($item->value)) {
                $this->updateOrInsertTreatmentRates($treatment, $item->value);
            }
        }

        $this->entityManager->persist($treatment);
        $this->entityManager->flush();
    }

    /**
     * @param Treatment $treatment
     * @param object $treatmentRates
     *
     * @return void
     * @throws \Exception
     */
    private function updateOrInsertTreatmentRates(Treatment $treatment, object $treatmentRates)
    {
        /*
         * [key] => Rate
         * [value] => stdClass Object
         *   [Map] => Array
         *     [0] => stdClass Object
         *       [item] => Array
         *         [0] => stdClass Object
         *           [key] => -- the key
         *           [value] => -- the value
         */
        foreach ($treatmentRates->Map as $key => $treatmentRatesDatum) {
            foreach ($treatmentRatesDatum->item as $item) {
                if ($item->key == 'InternalId') {
                    $findedByInternalId = $this->treatmentRateRepository->findOneByInternalId($item->value);
                    if ($findedByInternalId) {
                        $treatmentRate = $this->treatmentRateRepository->findOneBy(['internalId' => $item->value]);
                        $this->updateTreatmentRate($treatment, $treatmentRate, $treatmentRatesDatum);
                    } else {
                        $this->insertTreatmentRate($treatment, $treatmentRatesDatum);
                    }
                }
            }
        }
    }

    /**
     * @param Treatment $treatment
     * @param TreatmentRate $treatmentRate
     * @param object $treatmentRatesDatum
     *
     * @return void
     * @throws \Exception
     */
    private function updateTreatmentRate(Treatment $treatment, TreatmentRate $treatmentRate, object $treatmentRatesDatum)
    {
        foreach ($treatmentRatesDatum->item as $item) {
            if ($item->key == 'Price') {
                $treatmentRate->setPrice(floatval($item->value));
            }

            if ($item->key == 'FromDate') {
                $treatmentRate->setFromDate(new \DateTime($item->value));
            }

            if ($item->key == 'ToDate') {
                $treatmentRate->setToDate(new \DateTime($item->value));
            }

            if ($item->key == 'FromTime') {
                $treatmentRate->setFromTime(new \DateTime($item->value));
            }

            if ($item->key == 'ToTime') {
                $treatmentRate->setToTime(new \DateTime($item->value));
            }

            if ($item->key == 'Description') {
                $treatmentRate->setDescription($item->value);
            }

            if ($item->key == 'Weekdays') {
                $treatmentRate->setWeekdays($item->value);
            }

            if ($item->key == 'Weekend') {
                $treatmentRate->setWeekend($item->value);
            }

            if ($item->key == 'InternalId') {
                $treatmentRate->setInternalId($item->value);
            }

            if ($item->key == 'InternalIdRate') {
                $treatmentRate->setInternalIdRate($item->value);
            }

            if ($item->key == 'InstallmentPaymentPlan') {
                $treatmentRate->setInstallmentPaymentPlan((bool) $item->value);
            }

            if ($item->key == 'Discount') {
                $treatmentRate->setDiscount((bool) $item->value);
            }
        }

        $treatmentRate->setTreatment($treatment);
        $this->entityManager->persist($treatmentRate);
        $this->entityManager->flush();
    }

    /**
     * @param Treatment $treatment
     * @param object $treatmentRatesDatum
     *
     * @return void
     * @throws \Exception
     */
    private function insertTreatmentRate(Treatment $treatment, object $treatmentRatesDatum)
    {
        $treatmentRate = new TreatmentRate();
        $treatmentRate->setProductId(0);
        $treatmentRate->setProductAttributeId(0);

        foreach ($treatmentRatesDatum->item as $item) {
            if ($item->key == 'Id') {
                $treatmentRate->setInternalId($item->value);
            }

            if ($item->key == 'Price') {
                $treatmentRate->setPrice(floatval($item->value));
            }

            if ($item->key == 'FromDate') {
                $treatmentRate->setFromDate(new \DateTime($item->value));
            }

            if ($item->key == 'ToDate') {
                $treatmentRate->setToDate(new \DateTime($item->value));
            }

            if ($item->key == 'FromTime') {
                $treatmentRate->setFromTime(new \DateTime($item->value));
            }

            if ($item->key == 'ToTime') {
                $treatmentRate->setToTime(new \DateTime($item->value));
            }

            if ($item->key == 'Description') {
                $treatmentRate->setDescription($item->value);
            }

            if ($item->key == 'Weekdays') {
                $treatmentRate->setWeekdays($item->value);
            }

            if ($item->key == 'Weekend') {
                $treatmentRate->setWeekend($item->value);
            }

            if ($item->key == 'InternalId') {
                $treatmentRate->setInternalId($item->value);
            }

            if ($item->key == 'InternalIdRate') {
                $treatmentRate->setInternalIdRate($item->value);
            }

            if ($item->key == 'InstallmentPaymentPlan') {
                $treatmentRate->setInstallmentPaymentPlan((bool) $item->value);
            }

            if ($item->key == 'Discount') {
                $treatmentRate->setDiscount((bool) $item->value);
            }
        }

        $treatmentRate->setTreatment($treatment);
        $this->entityManager->persist($treatmentRate);
        $this->entityManager->flush();
    }
}
