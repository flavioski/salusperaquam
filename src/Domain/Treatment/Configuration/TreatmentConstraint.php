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

namespace Flavioski\Module\SalusPerAquam\Domain\Treatment\Configuration;

/**
 * Stores treatment form constraints configuration values
 */
final class TreatmentConstraint
{
    /**
     * Maximum length for treatment name (value is constrained by database)
     */
    public const MAX_TREATMENT_NAME_LENGTH = 128;

    /**
     * Maximum length for code (value is constrained by database)
     */
    public const MAX_CODE_LENGTH = 128;

    /**
     * Maximum length for price (value is constrained by database)
     */
    public const MIN_PRICE_VALUE = '0.00';

    /**
     * Maximum length for price (value is constrained by database)
     */
    public const MAX_PRICE_VALUE = '10000.00';

    /**
     * Step for price (value is constrained by database)
     */
    public const STEP_PRICE_VALUE = '0.01';

    /**
     * DNI field value regexp validation pattern
     */
    public const DNI_LITE_PATTERN = '/^[0-9A-Za-z-.]{1,16}$/U';

    /**
     * Prevents class to be instantiated
     */
    private function __construct()
    {
    }
}
