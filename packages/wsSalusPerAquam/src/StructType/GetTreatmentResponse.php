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

use WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetTreatmentResponse StructType
 *
 * @version 1.0
 * @date 2022/04/01
 */
class GetTreatmentResponse extends AbstractStructBase
{
    /**
     * The Result
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var complexType
     */
    public $Result;
    /**
     * The Success
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var bool
     */
    public $Success;

    /**
     * Constructor method for GetTreatmentResponse
     *
     * @uses GetTreatmentResponse::setResult()
     * @uses GetTreatmentResponse::setSuccess()
     *
     * @param complexType $result
     * @param bool $success
     */
    public function __construct(complexType $result = null, $success = null)
    {
        $this
            ->setResult($result)
            ->setSuccess($success);
    }

    /**
     * Get Result value
     *
     * @return complexType
     */
    public function getResult()
    {
        return $this->Result;
    }

    /**
     * Set Result value
     *
     * @param complexType $result
     *
     * @return \wsSalusPerAquam\StructType\GetTreatmentResponse
     */
    public function setResult(complexType $result = null)
    {
        $this->Result = $result;

        return $this;
    }

    /**
     * Get Success value
     *
     * @return bool
     */
    public function getSuccess()
    {
        return $this->Success;
    }

    /**
     * Set Success value
     *
     * @param bool $success
     *
     * @return \wsSalusPerAquam\StructType\GetTreatmentResponse
     */
    public function setSuccess($success = null)
    {
        // validation for constraint: boolean
        if (!is_null($success) && !is_bool($success)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($success, true), gettype($success)), __LINE__);
        }
        $this->Success = $success;

        return $this;
    }
}
