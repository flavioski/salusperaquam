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

namespace Flavioski\Module\SalusPerAquam\Domain\Treatment\ValueObject;

use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\InvalidTreatmentIdException;

class TreatmentId
{
    private $treatmentId;

    /**
     * TreatmentId constructor.
     *
     * @param int $treatmentId
     *
     * @throws InvalidTreatmentIdException
     */
    public function __construct($treatmentId)
    {
        $this->assertIntegerIsGreaterThanZero($treatmentId);

        $this->treatmentId = $treatmentId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->treatmentId;
    }

    /**
     * @param int $treatmentId
     *
     * @throws InvalidTreatmentIdException
     */
    private function assertIntegerIsGreaterThanZero($treatmentId)
    {
        if (!is_numeric($treatmentId) || 0 > $treatmentId) {
            throw new InvalidTreatmentIdException(sprintf('Invalid treatment id %s supplied. Treatment id must be positive integer.', var_export($treatmentId, true)));
        }
    }
}
