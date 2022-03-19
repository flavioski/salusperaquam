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
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class TreatmentFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var TreatmentRepository
     */
    private $treatmentRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param TreatmentRepository $treatmentRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        TreatmentRepository $treatmentRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->treatmentRepository = $treatmentRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $treatment = new Treatment();
        $treatment->setName($data['name']);
        $treatment->setCode($data['code']);
        $treatment->setPrice($data['price']);
        $treatment->setActive($data['active']);

        $this->entityManager->persist($treatment);
        $this->entityManager->flush();

        return $treatment->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $treatment = $this->treatmentRepository->findOneById($id);
        $treatment->setName($data['name']);
        $treatment->setCode($data['code']);
        $treatment->setPrice($data['price']);
        $treatment->setActive($data['active']);

        $this->entityManager->flush();

        return $treatment->getId();
    }
}
