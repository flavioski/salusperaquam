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
 * This class stands for AddSaleRequest StructType
 *
 * @version 1.0
 * @date 2022/04/01
 */
class AddSaleRequest extends AbstractStructBase
{
    /**
     * The username
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $username;
    /**
     * The password
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $password;
    /**
     * The customer_firstname
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $customer_firstname;
    /**
     * The customer_lastname
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $customer_lastname;
    /**
     * The customer_dni
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $customer_dni;
    /**
     * The customer_email
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $customer_email;
    /**
     * The sale_code
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $sale_code;
    /**
     * The sale_total
     * Meta information extracted from the WSDL
     * - maxOccurs: 1
     * - minOccurs: 1
     *
     * @var string
     */
    public $sale_total;
    /**
     * The sale_detail
     * Meta information extracted from the WSDL
     * - minOccurs: 1
     *
     * @var \wsSalusPerAquam\StructType\Sale_detail
     */
    public $sale_detail;

    /**
     * Constructor method for AddSaleRequest
     *
     * @uses AddSaleRequest::setUsername()
     * @uses AddSaleRequest::setPassword()
     * @uses AddSaleRequest::setCustomer_firstname()
     * @uses AddSaleRequest::setCustomer_lastname()
     * @uses AddSaleRequest::setCustomer_dni()
     * @uses AddSaleRequest::setCustomer_email()
     * @uses AddSaleRequest::setSale_code()
     * @uses AddSaleRequest::setSale_total()
     * @uses AddSaleRequest::setSale_detail()
     *
     * @param string $username
     * @param string $password
     * @param string $customer_firstname
     * @param string $customer_lastname
     * @param string $customer_dni
     * @param string $customer_email
     * @param string $sale_code
     * @param string $sale_total
     * @param \wsSalusPerAquam\StructType\Sale_detail $sale_detail
     */
    public function __construct($username = null, $password = null, $customer_firstname = null, $customer_lastname = null, $customer_dni = null, $customer_email = null, $sale_code = null, $sale_total = null, Sale_detail $sale_detail = null)
    {
        $this
            ->setUsername($username)
            ->setPassword($password)
            ->setCustomer_firstname($customer_firstname)
            ->setCustomer_lastname($customer_lastname)
            ->setCustomer_dni($customer_dni)
            ->setCustomer_email($customer_email)
            ->setSale_code($sale_code)
            ->setSale_total($sale_total)
            ->setSale_detail($sale_detail);
    }

    /**
     * Get username value
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username value
     *
     * @param string $username
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setUsername($username = null)
    {
        // validation for constraint: string
        if (!is_null($username) && !is_string($username)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($username, true), gettype($username)), __LINE__);
        }
        $this->username = $username;

        return $this;
    }

    /**
     * Get password value
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password value
     *
     * @param string $password
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setPassword($password = null)
    {
        // validation for constraint: string
        if (!is_null($password) && !is_string($password)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($password, true), gettype($password)), __LINE__);
        }
        $this->password = $password;

        return $this;
    }

    /**
     * Get customer_firstname value
     *
     * @return string
     */
    public function getCustomer_firstname()
    {
        return $this->customer_firstname;
    }

    /**
     * Set customer_firstname value
     *
     * @param string $customer_firstname
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setCustomer_firstname($customer_firstname = null)
    {
        // validation for constraint: string
        if (!is_null($customer_firstname) && !is_string($customer_firstname)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customer_firstname, true), gettype($customer_firstname)), __LINE__);
        }
        $this->customer_firstname = $customer_firstname;

        return $this;
    }

    /**
     * Get customer_lastname value
     *
     * @return string
     */
    public function getCustomer_lastname()
    {
        return $this->customer_lastname;
    }

    /**
     * Set customer_lastname value
     *
     * @param string $customer_lastname
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setCustomer_lastname($customer_lastname = null)
    {
        // validation for constraint: string
        if (!is_null($customer_lastname) && !is_string($customer_lastname)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customer_lastname, true), gettype($customer_lastname)), __LINE__);
        }
        $this->customer_lastname = $customer_lastname;

        return $this;
    }

    /**
     * Get customer_dni value
     *
     * @return string
     */
    public function getCustomer_dni()
    {
        return $this->customer_dni;
    }

    /**
     * Set customer_dni value
     *
     * @param string $customer_dni
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setCustomer_dni($customer_dni = null)
    {
        // validation for constraint: string
        if (!is_null($customer_dni) && !is_string($customer_dni)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customer_dni, true), gettype($customer_dni)), __LINE__);
        }
        $this->customer_dni = $customer_dni;

        return $this;
    }

    /**
     * Get customer_email value
     *
     * @return string
     */
    public function getCustomer_email()
    {
        return $this->customer_email;
    }

    /**
     * Set customer_email value
     *
     * @param string $customer_email
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setCustomer_email($customer_email = null)
    {
        // validation for constraint: string
        if (!is_null($customer_email) && !is_string($customer_email)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($customer_email, true), gettype($customer_email)), __LINE__);
        }
        $this->customer_email = $customer_email;

        return $this;
    }

    /**
     * Get sale_code value
     *
     * @return string
     */
    public function getSale_code()
    {
        return $this->sale_code;
    }

    /**
     * Set sale_code value
     *
     * @param string $sale_code
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setSale_code($sale_code = null)
    {
        // validation for constraint: string
        if (!is_null($sale_code) && !is_string($sale_code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($sale_code, true), gettype($sale_code)), __LINE__);
        }
        $this->sale_code = $sale_code;

        return $this;
    }

    /**
     * Get sale_total value
     *
     * @return string
     */
    public function getSale_total()
    {
        return $this->sale_total;
    }

    /**
     * Set sale_total value
     *
     * @param string $sale_total
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setSale_total($sale_total = null)
    {
        // validation for constraint: string
        if (!is_null($sale_total) && !is_string($sale_total)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($sale_total, true), gettype($sale_total)), __LINE__);
        }
        $this->sale_total = $sale_total;

        return $this;
    }

    /**
     * Get sale_detail value
     *
     * @return \wsSalusPerAquam\StructType\Sale_detail|null
     */
    public function getSale_detail()
    {
        return $this->sale_detail;
    }

    /**
     * Set sale_detail value
     *
     * @param \wsSalusPerAquam\StructType\Sale_detail $sale_detail
     *
     * @return \wsSalusPerAquam\StructType\AddSaleRequest
     */
    public function setSale_detail(Sale_detail $sale_detail = null)
    {
        $this->sale_detail = $sale_detail;

        return $this;
    }
}
