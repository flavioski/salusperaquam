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

namespace SalusPerAquam\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class ConfigurationController extends FrameworkBundleAdminController
{
    public function demoAction()
    {
        return $this->render('@Modules/salusperaquam/templates/admin/demo.html.twig');
    }
}