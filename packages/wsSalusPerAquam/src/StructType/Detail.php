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

namespace wsSalusPerAquam\StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for detail StructType
 * @subpackage Structs
 * @version 1.0
 * @date 2022/04/01
 */
class Detail extends AbstractStructBase
{
    /**
     * The treatment_code
     * Meta information extracted from the WSDL
     * - minOccurs: 1
     * @var string
     */
    public $treatment_code;
    /**
     * The quantity
     * Meta information extracted from the WSDL
     * - minOccurs: 1
     * @var int
     */
    public $quantity;
    /**
     * Constructor method for detail
     * @uses Detail::setTreatment_code()
     * @uses Detail::setQuantity()
     * @param string $treatment_code
     * @param int $quantity
     */
    public function __construct($treatment_code = null, $quantity = null)
    {
        $this
            ->setTreatment_code($treatment_code)
            ->setQuantity($quantity);
    }
    /**
     * Get treatment_code value
     * @return string
     */
    public function getTreatment_code()
    {
        return $this->treatment_code;
    }
    /**
     * Set treatment_code value
     * @param string $treatment_code
     * @return \wsSalusPerAquam\StructType\Detail
     */
    public function setTreatment_code($treatment_code = null)
    {
        // validation for constraint: string
        if (!is_null($treatment_code) && !is_string($treatment_code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($treatment_code, true), gettype($treatment_code)), __LINE__);
        }
        $this->treatment_code = $treatment_code;
        return $this;
    }
    /**
     * Get quantity value
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    /**
     * Set quantity value
     * @param int $quantity
     * @return \wsSalusPerAquam\StructType\Detail
     */
    public function setQuantity($quantity = null)
    {
        // validation for constraint: int
        if (!is_null($quantity) && !(is_int($quantity) || ctype_digit($quantity))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($quantity, true), gettype($quantity)), __LINE__);
        }
        $this->quantity = $quantity;
        return $this;
    }
}
