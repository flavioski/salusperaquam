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

namespace Flavioski\Module\SalusPerAquam\ConstraintValidator\Constraints;

use Flavioski\Module\SalusPerAquam\ConstraintValidator\TreatmentProductAttributeRequiredValidator;
use Symfony\Component\Validator\Constraint;

class TreatmentProductAttributeRequired extends Constraint
{
    public $message = 'A treatment with product containing combination must have a attribute selected.';

    public $id_product;

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return TreatmentProductAttributeRequiredValidator::class;
    }

    public function getRequiredOptions()
    {
        return ['id_product'];
    }
}
