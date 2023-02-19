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

namespace Flavioski\Module\SalusPerAquam\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\DemoRestricted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends FrameworkBundleAdminController
{
    /**
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->get('flavioski.module.salusperaquam.form.identifiable_object.data_provider.configuration_form_handler')->getForm();

        return $this->render('@Modules/salusperaquam/views/templates/admin/configuration/index.html.twig', [
            'layoutHeaderToolbarBtn' => [],
            'layoutTitle' => $this->trans('Configurations', 'Modules.Salusperaquam.Admin'),
            'requireAddonsSearch' => true,
            'requireBulkActions' => false,
            'showContentHeader' => true,
            'enableSidebar' => true,
            'help_link' => '',
            'requireFilterStatus' => false,
            'generalForm' => $form->createView(),
        ]);
    }

    /**
     * Process the Parameter form.
     *
     * @AdminSecurity("is_granted(['read','update','create','delete'], request.get('_legacy_controller'))", message="You do not have permission to update this.")
     * @DemoRestricted(redirectRoute="admin")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function processFormAction(Request $request)
    {
        $formHandler = $this->get('flavioski.module.salusperaquam.form.identifiable_object.data_provider.configuration_form_handler');

        $form = $formHandler->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($errors = $formHandler->save($data)) {
                $this->flashErrors($errors);

                return $this->redirectToRoute('flavioski_salusperaquam_configuration_index');
            }

            $this->addFlash('success', $this->trans('Update successful', 'Admin.Notifications.Success'));
        }

        return $this->render('@Modules/salusperaquam/views/templates/admin/configuration/index.html.twig', [
            'layoutTitle' => $this->trans('Customers', 'Admin.Navigation.Menu'),
            'requireAddonsSearch' => true,
            'enableSidebar' => true,
            'help_link' => '',
            'generalForm' => $form->createView(),
        ]);
    }
}
