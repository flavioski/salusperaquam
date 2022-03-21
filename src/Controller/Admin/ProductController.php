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

namespace Flavioski\Module\SalusPerAquam\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Responsible for handling product combinations data
 */
class ProductController extends FrameworkBundleAdminController
{
    /**
     * Provides product combinations in json response
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getCombinationsAction(Request $request)
    {
        try {
            $productId = (int) $request->query->get('id_country');
            $combinationsProvider = $this->get('flavioski.module.salusperaquam.form.choice_provider.product_attribute_by_id');
            $combinations = $combinationsProvider->getChoices([
                'id_product' => $productId,
            ]);

            return $this->json([
                'combinations' => $combinations,
            ]);
        } catch (Exception $e) {
            return $this->json([
                'message' => $this->getErrorMessageForException($e, []),
            ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
