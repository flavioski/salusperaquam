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

namespace Flavioski\Module\SalusPerAquam\WebService;

use Flavioski\Module\SalusPerAquam\WebService\Exception\WebServiceException;
use SoapClient;
use SoapFault;
use wsSalusPerAquam\ServiceType\Add as ServiceAddSale;
use wsSalusPerAquam\StructType\AddSaleRequest;
use wsSalusPerAquam\StructType\AddSaleResponse;
use wsSalusPerAquam\StructType\Detail;
use wsSalusPerAquam\StructType\Sale_detail;

class AddSale implements ServiceInterface
{
    public const MODULE_NAME = 'salusperaquam';
    public const CONFIGURATION_TYPE = 'data';
    public const WSDL_FOLDER = 'Resources';
    public const WSDL_FILE = 'wcf.wsdl';

    /**
     * @var MyWebService
     */
    private $myWebService;

    private $customer_firstname;
    private $customer_lastname;
    private $customer_dni;
    private $customer_email;
    private $sale_code;
    private $sale_total;
    private $sale_detail;
    private $detail;
    private $total_detail;

    /**
     * @param MyWebService $myWebService
     */
    public function __construct(MyWebService $myWebService)
    {
        $this->myWebService = $myWebService;
    }

    /**
     * @return WebServiceException|AddSaleResponse
     */
    public function Request()
    {
        $wsdl = $this->myWebService->handle();

        try {
            $soapclient = new SoapClient($this->myWebService->getUrl(), $this->myWebService->getParams());
        } catch (SoapFault $fault) {
            return new WebServiceException(sprintf(
                'Invalid call web service: "%s"',
                $fault->getMessage()
            ), WebServiceException::FAILED_CONNECT
            );
        }

        $saleDetail = new Sale_detail();
        if (count($this->total_detail)) {
            foreach ($this->total_detail as $detail) {
                $dt = new Detail();
                $dt->setTreatment_code($detail['treatment_code']);
                $dt->setQuantity($detail['quantity']);
                $saleDetail->addToDetail($dt);
            }
        }

        $username = $wsdl['wsdl']['wsdl_login'];
        $password = $wsdl['wsdl']['wsdl_password'];

        $saleRequest = new AddSaleRequest(
            $username, $password, $this->customer_firstname, $this->customer_lastname,
            $this->customer_dni, $this->customer_email, $this->sale_code, $this->sale_total, $saleDetail
        );

        $add = new ServiceAddSale($wsdl['wsdlOption']);
        $add->setSoapClient($soapclient);

        if ($add->AddSale($saleRequest) !== false) {
            return $add->getResult();
        } else {
            return new WebServiceException(sprintf(
                'There are some errors: "%s',
                implode(',', $add->getLastError())
            ), WebServiceException::FAILED_SEND_DATA
            );
        }
    }

    public function Response()
    {
        // TODO: Implement Response() method.
    }

    /**
     * @return mixed
     */
    public function getCustomerFirstname()
    {
        return $this->customer_firstname;
    }

    public function setCustomerFirstname($customer_firstname)
    {
        $this->customer_firstname = $customer_firstname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerLastname()
    {
        return $this->customer_lastname;
    }

    public function setCustomerLastname($customer_lastname)
    {
        $this->customer_lastname = $customer_lastname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerDni()
    {
        return $this->customer_dni;
    }

    public function setCustomerDni($customer_dni)
    {
        $this->customer_dni = $customer_dni;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->customer_email;
    }

    public function setCustomerEmail($customer_email)
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSaleCode()
    {
        return $this->sale_code;
    }

    public function setSaleCode($sale_code)
    {
        $this->sale_code = $sale_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSaleTotal()
    {
        return $this->sale_total;
    }

    public function setSaleTotal($sale_total)
    {
        $this->sale_total = $sale_total;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSaleDetail()
    {
        return $this->sale_detail;
    }

    /**
     * @param Sale_detail $sale_detail
     *
     * @return $this
     */
    public function setSaleDetail(Sale_detail $sale_detail)
    {
        $this->sale_detail = $sale_detail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param Detail $detail
     *
     * @return $this
     */
    public function setDetail(Detail $detail)
    {
        $this->detail[] = $detail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalDetail()
    {
        return $this->total_detail;
    }

    /**
     * @param array $detail
     *
     * @return $this
     */
    public function addTotalDetail(array $detail)
    {
        $this->total_detail[] = $detail;

        return $this;
    }
}
