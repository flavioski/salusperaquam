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

namespace Flavioski\Module\SalusPerAquam\Domain\Treatment\CommandHandler;

use Flavioski\Module\SalusPerAquam\Domain\Treatment\Command\ToggleIsActiveTreatmentCommand;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\CannotToggleActiveTreatmentStatusException;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\TreatmentNotFoundException;
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository;
use PrestaShopException;
use Doctrine\ORM\EntityManagerInterface;


class ToggleIsActiveTreatmentHandler extends AbstractTreatmentHandler
{
    /**
     * @var TreatmentRepository
     */
    private $treatmentRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TreatmentRepository $treatmentRepository, EntityManagerInterface $entityManager)
    {
        $this->treatmentRepository = $treatmentRepository;
        $this->entityManager = $entityManager;
    }

    public function handle(ToggleIsActiveTreatmentCommand $command)
    {
        $treatmentId = $command->getTreatmentId()->getValue();
        $treatment = $this->treatmentRepository->find($treatmentId);

        if ($treatment->getId() !== $treatmentId) {
            throw new TreatmentNotFoundException(sprintf('Treatment with id "%id" was not found', $treatmentId));
        }

        if ($treatment->isActive() != $command->getActive()) {

            $treatment->setActive($command->getActive());

            if (false === $this->entityManager->flush()) {
                throw new CannotToggleActiveTreatmentStatusException(sprintf('Failed to change status for treatment with id "%s"', $command->getTreatmentId()->getValue()));
            }
        }
    }
}
