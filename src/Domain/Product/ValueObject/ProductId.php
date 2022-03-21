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
 * @author    Flavio Pellizzer <flappio.pelliccia@gmail.com>
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Domain\Product\ValueObject;

use Flavioski\Module\SalusPerAquam\Domain\Product\Exception\ProductConstraintException;

class ProductId
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     *
     * @throws ProductConstraintException
     */
    public function __construct(int $id)
    {
        $this->assertPositiveInt($id);
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->id;
    }

    /**
     * @param int $value
     *
     * @throws ProductConstraintException
     */
    private function assertPositiveInt(int $value)
    {
        if (0 > $value) {
            throw new ProductConstraintException(sprintf('Invalid product id "%s".', var_export($value, true)), ProductConstraintException::INVALID_ID);
        }
    }
}
