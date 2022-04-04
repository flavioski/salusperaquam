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

namespace wsSalusPerAquam\ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Get ServiceType
 * @subpackage Services
 * @version 1.0
 * @date 2022/04/01
 */
class Get extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named GetTreatment
     * Meta information extracted from the WSDL
     * - documentation: Get treatments list.
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \wsSalusPerAquam\StructType\GetTreatmentRequest $parameters
     * @return \wsSalusPerAquam\StructType\GetTreatmentResponse|bool
     */
    public function GetTreatment(\wsSalusPerAquam\StructType\GetTreatmentRequest $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->GetTreatment($parameters));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \wsSalusPerAquam\StructType\GetTreatmentResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
