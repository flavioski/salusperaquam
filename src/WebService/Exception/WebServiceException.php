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

namespace Flavioski\Module\SalusPerAquam\WebService\Exception;

use Exception;

class WebServiceException extends Exception
{
    /**
     * When fails to connect web service
     */
    public const FAILED_CONNECT = 10;

    /**
     * When fails to sync data's
     */
    public const FAILED_SYNC_DATA = 20;

    /**
     * When fails to send data's
     */
    public const FAILED_SEND_DATA = 30;

    /**
     * When fails to get data's
     */
    public const FAILED_GET_DATA = 40;
}
