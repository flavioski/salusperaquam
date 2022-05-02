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

use WsdlToPhp\PackageBase\AbstractSoapClientBase;
use wsSalusPerAquam\ClassMap;

class MyWebService implements MyWebServiceInterface
{
    /**
     * @var bool
     */
    private $configurationTestEnabled;

    /**
     * @var string
     */
    private $configurationTestUrl;

    /**
     * @var string
     */
    private $configurationTestUsername;

    /**
     * @var string
     */
    private $configurationTestPassword;

    /**
     * @var bool
     */
    private $configurationProductionEnabled;

    /**
     * @var string
     */
    private $configurationProductionUrl;

    /**
     * @var string
     */
    private $configurationProductionUsername;

    /**
     * @var string
     */
    private $configurationProductionPassword;

    /**
     * @param bool $configurationTestEnabled
     * @param string $configurationTestUrl
     * @param string $configurationTestUsername
     * @param string $configurationTestPassword
     * @param bool $configurationProductionEnabled
     * @param string $configurationProductionUrl
     * @param string $configurationProductionUsername
     * @param string $configurationProductionPassword
     */
    public function __construct(
        bool $configurationTestEnabled,
        string $configurationTestUrl,
        string $configurationTestUsername,
        string $configurationTestPassword,
        bool $configurationProductionEnabled,
        string $configurationProductionUrl,
        string $configurationProductionUsername,
        string $configurationProductionPassword
    ) {
        $this->configurationTestEnabled = $configurationTestEnabled;
        $this->configurationTestUrl = $configurationTestUrl;
        $this->configurationTestUsername = $configurationTestUsername;
        $this->configurationTestPassword = $configurationTestPassword;
        $this->configurationProductionEnabled = $configurationProductionEnabled;
        $this->configurationProductionUrl = $configurationProductionUrl;
        $this->configurationProductionUsername = $configurationProductionUsername;
        $this->configurationProductionPassword = $configurationProductionPassword;
    }

    /**
     * @return array[]
     */
    public function handle()
    {
        $wsdl[AbstractSoapClientBase::WSDL_URL] = $this->getUrl() ? $this->getUrl() : AbstractSoapClientBase::WSDL_URL;
        $wsdl[AbstractSoapClientBase::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
        $wsdl[AbstractSoapClientBase::WSDL_TRACE] = true;
        $wsdl[AbstractSoapClientBase::WSDL_EXCEPTIONS] = true;
        $wsdl[AbstractSoapClientBase::WSDL_LOGIN] = $this->getLogin();
        $wsdl[AbstractSoapClientBase::WSDL_PASSWORD] = $this->getPassword();

        $wsdlOptions = [
            AbstractSoapClientBase::WSDL_URL => $this->getUrl(),
            AbstractSoapClientBase::WSDL_URI => $this->getUrl(),
            AbstractSoapClientBase::WSDL_CACHE_WSDL => WSDL_CACHE_NONE,
            AbstractSoapClientBase::WSDL_LOCATION => AbstractSoapClientBase::WSDL_LOCATION,
            AbstractSoapClientBase::WSDL_CLASSMAP => ClassMap::get(),
        ];

        return ['wsdl' => $wsdl, 'wsdlOption' => $wsdlOptions];
    }

    /**
     * @return bool
     */
    public function isTest()
    {
        return $this->configurationTestEnabled;
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->configurationProductionEnabled;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $opts = [
            'ssl' => [
                'ciphers' => 'RC4-SHA',
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        return [
            'encoding' => 'UTF-8',
            'debug' => false,
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_2,
            'trace' => 1,
            'exceptions' => 0,
            'connection_timeout' => 180,
            'keep_alive' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create($opts),
        ];
    }

    /**
     * @return string|void
     */
    public function getUrl()
    {
        if ($this->isTest()) {
            return $this->getConfigurationTestUrl();
        }

        if ($this->isProduction()) {
            return $this->getConfigurationProductionUrl();
        }
    }

    /**
     * @return string|void
     */
    public function getLogin()
    {
        if ($this->isTest()) {
            return $this->getConfigurationTestLogin();
        }

        if ($this->isProduction()) {
            return $this->getConfigurationProductionLogin();
        }
    }

    /**
     * @return string|void
     */
    public function getPassword()
    {
        if ($this->isTest()) {
            return $this->getConfigurationTestPassword();
        }

        if ($this->isProduction()) {
            return $this->getConfigurationProductionPassword();
        }
    }

    /**
     * @return string
     */
    private function getConfigurationTestUrl()
    {
        return $this->configurationTestUrl;
    }

    /**
     * @return string
     */
    private function getConfigurationProductionUrl()
    {
        return $this->configurationProductionUrl;
    }

    /**
     * @return string
     */
    private function getConfigurationTestLogin()
    {
        return $this->configurationTestUsername;
    }

    /**
     * @return string
     */
    private function getConfigurationTestPassword()
    {
        return $this->configurationTestPassword;
    }

    /**
     * @return string
     */
    private function getConfigurationProductionLogin()
    {
        return $this->configurationProductionUsername;
    }

    /**
     * @return string
     */
    private function getConfigurationProductionPassword()
    {
        return $this->configurationProductionPassword;
    }
}
