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
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     *
     * @var \wsSalusPerAquam\StructType\Detail[]
     */
    public $detail;

    /**
     * Constructor method for sale_detail
     *
     * @uses Sale_detail::setDetail()
     *
     * @param \wsSalusPerAquam\StructType\Detail[] $detail
     */
    public function __construct(array $detail = [])
    {
        $this
            ->setDetail($detail);
    }

    /**
     * Get detail value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     *
     * @return \wsSalusPerAquam\StructType\Detail[]|null
     */
    public function getDetail()
    {
        return isset($this->detail) ? $this->detail : null;
    }

    /**
     * This method is responsible for validating the values passed to the setDetail method
     * This method is willingly generated in order to preserve the one-line inline validation within the setDetail method
     *
     * @param array $values
     *
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateDetailForArrayConstraintsFromSetDetail(array $values = [])
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $sale_detailDetailItem) {
            // validation for constraint: itemType
            if (!$sale_detailDetailItem instanceof \wsSalusPerAquam\StructType\Detail) {
                $invalidValues[] = is_object($sale_detailDetailItem) ? get_class($sale_detailDetailItem) : sprintf('%s(%s)', gettype($sale_detailDetailItem), var_export($sale_detailDetailItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The detail property can only contain items of type \wsSalusPerAquam\StructType\Detail, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);

        return $message;
    }

    /**
     * Set detail value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     *
     * @throws \InvalidArgumentException
     *
     * @param \wsSalusPerAquam\StructType\Detail[] $detail
     *
     * @return \wsSalusPerAquam\StructType\Sale_detail
     */
    public function setDetail(array $detail = [])
    {
        // validation for constraint: array
        if ('' !== ($detailArrayErrorMessage = self::validateDetailForArrayConstraintsFromSetDetail($detail))) {
            throw new \InvalidArgumentException($detailArrayErrorMessage, __LINE__);
        }
        if (is_null($detail) || (is_array($detail) && empty($detail))) {
            unset($this->detail);
        } else {
            $this->detail = $detail;
        }

        return $this;
    }

    /**
     * Add item to detail value
     *
     * @throws \InvalidArgumentException
     *
     * @param \wsSalusPerAquam\StructType\Detail $item
     *
     * @return \wsSalusPerAquam\StructType\Sale_detail
     */
    public function addToDetail(Detail $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \wsSalusPerAquam\StructType\Detail) {
            throw new \InvalidArgumentException(sprintf('The detail property can only contain items of type \wsSalusPerAquam\StructType\Detail, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->detail[] = $item;

        return $this;
    }
}
