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
use wsSalusPerAquam\ServiceType\Get as ServiceGetTreatment;
use wsSalusPerAquam\StructType\GetTreatmentRequest;
use wsSalusPerAquam\StructType\GetTreatmentResponse;

class GetTreatment implements ServiceInterface
{
    public const MODULE_NAME = 'salusperaquam';
    public const CONFIGURATION_TYPE = 'data';
    public const WSDL_FOLDER = 'Resources';
    public const WSDL_FILE = 'wcf.wsdl';

    /**
     * @var MyWebService
     */
    private $myWebService;

    /**
     * @param MyWebService $myWebService
     */
    public function __construct(MyWebService $myWebService)
    {
        $this->myWebService = $myWebService;
    }

    /**
     * @return WebServiceException|GetTreatmentResponse
     */
    public function Request()
    {
        $wsdl = $this->myWebService->connect();

        try {
            $soapclient = new SoapClient($this->myWebService->getUrl(), $this->myWebService->getParams());
        } catch (SoapFault $fault) {
            return new WebServiceException(sprintf(
                'Invalid call web service: "%s"',
                $fault->getMessage()
                ), WebServiceException::FAILED_CONNECT
            );
        }

        $username = $wsdl['wsdl']['wsdl_login'];
        $password = $wsdl['wsdl']['wsdl_password'];

        $treatmentRequest = new GetTreatmentRequest($username, $password);

        $get = new ServiceGetTreatment($wsdl['wsdlOption']);
        $get->setSoapClient($soapclient);

        if ($get->GetTreatment($treatmentRequest) !== false) {
            return $get->getResult();
        } else {
            return new WebServiceException(sprintf(
                'There are some errors: "%s',
                implode(',', $get->getLastError())
            ), WebServiceException::FAILED_GET_DATA
            );
        }
    }

    public function Response()
    {
        // TODO: Implement Response() method.
    }
}
