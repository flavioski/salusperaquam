<?php

namespace WsdlToPhp\PackageBase\Tests;

use SoapFault;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;

class SoapClient extends AbstractSoapClientBase
{
    public function getSoapClientClassName($soapClientClassName = null)
    {
        return parent::getSoapClientClassName(empty($soapClientClassName) ? '\WsdlToPhp\PackageBase\Tests\Client' : $soapClientClassName);
    }
    /**
     * @return Client
     */
    public function getSoapClient()
    {
        return parent::getSoapClient();
    }
    /**
     *
     */
    public function search()
    {
        try {
            $this->getSoapClient()->search();
        } catch (SoapFault $soapFault) {
            $this->setResult(null);
            $this->saveLastError(__METHOD__, $soapFault);
        }
    }
}
