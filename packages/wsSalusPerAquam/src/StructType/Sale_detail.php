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
 * This class stands for sale_detail StructType
 *
 * @version 1.0
 * @date 2022/04/01
 */
class Sale_detail extends AbstractStructBase
{
    /**
     * The detail
     * Meta information extracted from the WSDL
     * - minOccurs: 1
     *
     * @var \wsSalusPerAquam\StructType\Detail
     */
    public $detail;

    /**
     * Constructor method for sale_detail
     *
     * @uses Sale_detail::setDetail()
     *
     * @param \wsSalusPerAquam\StructType\Detail $detail
     */
    public function __construct(Detail $detail = null)
    {
        $this
            ->setDetail($detail);
    }

    /**
     * Get detail value
     *
     * @return \wsSalusPerAquam\StructType\Detail
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set detail value
     *
     * @param \wsSalusPerAquam\StructType\Detail $detail
     *
     * @return \wsSalusPerAquam\StructType\Sale_detail
     */
    public function setDetail(Detail $detail = null)
    {
        $this->detail = $detail;

        return $this;
    }
}
