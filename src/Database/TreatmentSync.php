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
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     * @param GetTreatment $getTreatment
     */
    public function __construct(
        TreatmentRepository $treatmentRepository,
        LangRepository $langRepository,
        EntityManagerInterface $entityManager,
        GetTreatment $getTreatment
    ) {
        $this->treatmentRepository = $treatmentRepository;
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

        $treatment->setActive(true);
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
        }

        $this->entityManager->persist($treatment);
        $this->entityManager->flush();
    }
}
