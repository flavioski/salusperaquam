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

namespace wsSalusPerAquam;

/**
 * Class which returns the class map definition
 * @package
 */
class ClassMap
{
    /**
     * Returns the mapping between the WSDL Structs and generated Structs' classes
     * This array is sent to the \SoapClient when calling the WS
     * @return string[]
     */
    final public static function get()
    {
        return array(
            'GetTreatmentRequest' => '\\wsSalusPerAquam\\StructType\\GetTreatmentRequest',
            'GetTreatmentResponse' => '\\wsSalusPerAquam\\StructType\\GetTreatmentResponse',
            'AddSaleRequest' => '\\wsSalusPerAquam\\StructType\\AddSaleRequest',
            'sale_detail' => '\\wsSalusPerAquam\\StructType\\Sale_detail',
            'detail' => '\\wsSalusPerAquam\\StructType\\Detail',
            'AddSaleResponse' => '\\wsSalusPerAquam\\StructType\\AddSaleResponse',
        );
    }
}
