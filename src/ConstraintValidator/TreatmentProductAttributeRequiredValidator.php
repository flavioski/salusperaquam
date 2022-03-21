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

namespace Flavioski\Module\SalusPerAquam\ConstraintValidator;

use Flavioski\Module\SalusPerAquam\ConstraintValidator\Constraints\TreatmentProductAttributeRequired;
use Flavioski\Module\SalusPerAquam\Domain\Product\ProductRequiredFieldsProviderInterface;
use Flavioski\Module\SalusPerAquam\Domain\Product\Exception\ProductConstraintException;
use Flavioski\Modules\SalusPerAquam\Domain\Product\ValueObject\ProductId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationInterface;

class TreatmentProductAttributeRequiredValidator extends ConstraintValidator
{
    /**
     * @var ProductRequiredFieldsProviderInterface
     */
    private $productRequiredFieldsProvider;

    /**
     * @param ProductRequiredFieldsProviderInterface $productRequiredFieldsProvider
     */
    public function __construct(ProductRequiredFieldsProviderInterface $productRequiredFieldsProvider)
    {
        $this->productRequiredFieldsProvider = $productRequiredFieldsProvider;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ProductConstraintException
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof TreatmentProductAttributeRequired)) {
            return;
        }
        $productId = new ProductId((int) $constraint->id_product);

        if ($this->productRequiredFieldsProvider->isCombinationsRequired($productId)) {
            $constraints = [
                new NotBlank([
                    'message' => $constraint->message,
                ]),
            ];

            /** @var ConstraintViolationInterface[] $violations */
            $violations = $this->context->getValidator()->validate($value, $constraints);
            foreach ($violations as $violation) {
                $this->context->buildViolation($violation->getMessage())
                    ->setTranslationDomain('Admin.Notifications.Error')
                    ->addViolation();
            }
        }
    }
}
