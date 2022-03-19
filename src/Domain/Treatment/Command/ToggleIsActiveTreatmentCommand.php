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

namespace Flavioski\Module\SalusPerAquam\Domain\Treatment\Command;

use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\InvalidTreatmentIdException;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\ValueObject\TreatmentId;

class ToggleIsActiveTreatmentCommand
{
    /**
     * @var TreatmentId
     */
    private $treatmentId;

    /**
     * @var bool
     */
    private $active;

    /**
     * ToggleIsActiveTreatmentCommand constructor.
     *
     * @param $treatmentId
     * @param bool $active
     *
     * @throws InvalidTreatmentIdException
     */
    public function __construct(int $treatmentId, bool $active)
    {
        $this->treatmentId = new TreatmentId($treatmentId);
        $this->active = $active;
    }

    /**
     * @return TreatmentId
     */
    public function getTreatmentId(): TreatmentId
    {
        return $this->treatmentId;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }
}
