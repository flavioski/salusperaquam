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

namespace Flavioski\Module\SalusPerAquam\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentToEuroTransformer implements DataTransformerInterface
{
    /**
     * Transforms cent to euro amount.
     *
     * @param  int|null $priceInCent
     * @return double
     */
    public function transform($priceInCent)
    {
        if (null === $priceInCent) {
            return;
        }

        $priceInEuro = number_format(($priceInCent /100), 2, '.', ' ');

        return $priceInEuro;
    }

    /**
     * Transforms euro to cent amount.
     *
     * @param  double|null $priceInEuro
     * @return int
     */
    public function reverseTransform($priceInEuro)
    {
        if (null === $priceInEuro) {
            return;
        }

        $priceInCent = (int)($priceInEuro * 100);

        return $priceInCent;
    }
}
