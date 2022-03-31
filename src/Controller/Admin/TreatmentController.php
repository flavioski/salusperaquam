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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Command\ToggleIsActiveTreatmentCommand;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\CannotToggleActiveTreatmentStatusException;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Exception\TreatmentException;
use Flavioski\Module\SalusPerAquam\Domain\Treatment\Query\GetTreatmentIsActive;
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\Grid\Definition\Factory\TreatmentGridDefinitionFactory;
use Flavioski\Module\SalusPerAquam\Grid\Filters\TreatmentFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\DemoRestricted;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TreatmentController extends FrameworkBundleAdminController
{
    /**
     * List treatments
     *
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     * @param TreatmentFilters $filters
     *
     * @return Response
     */
    public function indexAction(Request $request, TreatmentFilters $filters)
    {
        $treatmentGridFactory = $this->get('flavioski.module.salusperaquam.grid.factory.treatments');
        $treatmentGrid = $treatmentGridFactory->getGrid($filters);

        return $this->render(
            '@Modules/salusperaquam/views/templates/admin/treatment/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Treatments', 'Modules.Salusperaquam.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'treatmentGrid' => $this->presentGrid($treatmentGrid),
            ]
        );
    }

    /**
     * Provides filters functionality.
     *
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('flavioski.module.salusperaquam.grid.definition.factory.treatments'),
            $request,
            TreatmentGridDefinitionFactory::GRID_ID,
            'flavioski_salusperaquam_treatment_index'
        );
    }

    /**
     * Generate treatments
     *
     * @AdminSecurity(
     *     "is_granted(['read','update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generateAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $generator = $this->get('flavioski.module.salusperaquam.treatments.generator');
            $generator->generateTreatments();
            $this->addFlash('success', $this->trans('Treatments were successfully generated.', 'Modules.Salusperaquam.Admin'));

            return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
        }

        return $this->render(
            '@Modules/salusperaquam/views/templates/admin/treatment/generate.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Treatments', 'Modules.Salusperaquam.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
            ]
        );
    }

    /**
     * Create treatment
     *
     * @AdminSecurity(
     *     "is_granted(['create', 'update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $treatmentFormBuilder = $this->get('flavioski.module.salusperaquam.form.identifiable_object.builder.treatment_form_builder');
        $formData = [];
        // Product needs to be preset before building form type because it is used to build combinations field choices
        if ($request->request->has('treatment') && isset($request->request->get('treatment')['id_product'])) {
            $formProductId = (int) $request->request->get('treatment')['id_product'];
            $formData['id_product'] = $formProductId;
        }
        $treatmentForm = $treatmentFormBuilder->getForm($formData);
        $treatmentForm->handleRequest($request);

        $treatmentFormHandler = $this->get('flavioski.module.salusperaquam.form.identifiable_object.handler.treatment_form_handler');
        $result = $treatmentFormHandler->handle($treatmentForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
        }

        return $this->render('@Modules/salusperaquam/views/templates/admin/treatment/create.html.twig', [
            'treatmentForm' => $treatmentForm->createView(),
        ]);
    }

    /**
     * Edit treatment
     *
     * @AdminSecurity(
     *     "is_granted(['update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     * @param int $treatmentId
     *
     * @return Response
     */
    public function editAction(Request $request, $treatmentId)
    {
        $treatmentFormBuilder = $this->get('flavioski.module.salusperaquam.form.identifiable_object.builder.treatment_form_builder');
        $formData = [];
        // Product needs to be preset before building form type because it is used to build combinations field choices
        if ($request->request->has('treatment') && isset($request->request->get('treatment')['id_product'])) {
            $formProductId = (int) $request->request->get('treatment')['id_product'];
            $formData['id_product'] = $formProductId;
        }
        $treatmentForm = $treatmentFormBuilder->getFormFor((int) $treatmentId, $formData);
        $treatmentForm->handleRequest($request);

        $treatmentFormHandler = $this->get('flavioski.module.salusperaquam.form.identifiable_object.handler.treatment_form_handler');
        $result = $treatmentFormHandler->handleFor((int) $treatmentId, $treatmentForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
        }

        return $this->render('@Modules/salusperaquam/views/templates/admin/treatment/edit.html.twig', [
            'treatmentForm' => $treatmentForm->createView(),
        ]);
    }

    /**
     * Delete treatment
     *
     * @AdminSecurity(
     *     "is_granted(['delete'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param int $treatmentId
     *
     * @return Response
     */
    public function deleteAction($treatmentId)
    {
        $repository = $this->get('flavioski.module.salusperaquam.repository.treatment_repository');
        try {
            $treatment = $repository->findOneById($treatmentId);
        } catch (EntityNotFoundException $e) {
            $treatment = null;
        }

        if (null !== $treatment) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($treatment);
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find treatment %treatment%',
                    'Modules.Salusperaquam.Admin',
                    ['%treatment%' => $treatmentId]
                )
            );
        }

        return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
    }

    /**
     * Delete bulk treatments
     *
     * @AdminSecurity(
     *     "is_granted(['delete'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteBulkAction(Request $request)
    {
        $treatmentIds = $request->request->get('treatment_bulk');
        $repository = $this->get('flavioski.module.salusperaquam.repository.treatment_repository');
        try {
            $treatments = $repository->findById($treatmentIds);
        } catch (EntityNotFoundException $e) {
            $treatments = null;
        }
        if (!empty($treatments)) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($treatments as $treatment) {
                $em->remove($treatment);
            }
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('The selection has been successfully deleted.', 'Admin.Notifications.Success')
            );
        }

        return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
    }

    /**
     * Toggles status.
     *
     * @AdminSecurity(
     *     "is_granted(['update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to read this.",
     *     redirectRoute="flavioski_salusperaquam_treatment_index"
     * )
     * @DemoRestricted(redirectRoute="flavioski_salusperaquam_treatment_index",
     *     message="You can't do this when demo mode is enabled.",
     *     domain="Admin.Global"
     * )
     *
     * @param int $treatmentId
     *
     * @return RedirectResponse
     */
    public function toggleStatusAction($treatmentId)
    {
        try {
            $isActive = $this->getQueryBus()->handle(new GetTreatmentIsActive((int) $treatmentId));

            $this->getCommandBus()->handle(new ToggleIsActiveTreatmentCommand((int) $treatmentId, !$isActive));

            $this->addFlash(
                'success',
                $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success')
            );
        } catch (TreatmentException $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessageMapping()));
        }

        return $this->redirectToRoute('flavioski_salusperaquam_treatment_index');
    }

    /**
     * @return array[]
     */
    private function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => $this->trans('Add new treatment', 'Modules.Salusperaquam.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('flavioski_salusperaquam_treatment_create'),
            ],
            'generate' => [
                'desc' => $this->trans('Generate treatments', 'Modules.Salusperaquam.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('flavioski_salusperaquam_treatment_generate'),
            ],
        ];
    }

    private function getErrorMessageMapping()
    {
        return [
            CannotToggleActiveTreatmentStatusException::class => [
                CannotToggleActiveTreatmentStatusException::FAILED_TOGGLE => $this->trans(
                    'An error occurred while updating the status.',
                    'Admin.Notifications.Error'
                ),
                CannotToggleActiveTreatmentStatusException::FAILED_BULK_TOGGLE => $this->trans(
                    'An error occurred while updating the status.',
                    'Admin.Notifications.Error'
                ),
            ],
        ];
    }
}
