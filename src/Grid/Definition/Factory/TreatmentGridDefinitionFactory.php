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

namespace Flavioski\Module\SalusPerAquam\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TreatmentGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    public const GRID_ID = 'treatment';

    /**
     * @var string $resetActionUrl
     */
    private $resetActionUrl;

    /**
     * @var string $redirectionUr
     */
    private $redirectionUr;

    /**
     * @param HookDispatcherInterface $hookDispatcher
     * @param string $resetActionUrl
     * @param string $redirectionUrl
     */
    public function __construct(
        HookDispatcherInterface $hookDispatcher,
        $resetActionUrl,
        $redirectionUrl
    ) {
        parent::__construct($hookDispatcher);
        $this->resetActionUrl = $resetActionUrl;
        $this->redirectionUr = $redirectionUrl;
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Treatments', [], 'Modules.Salusperaquam.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new BulkActionColumn('bulk'))
                ->setOptions([
                    'bulk_field' => 'id_treatment',
                ])
            )
            ->add((new DataColumn('id_treatment'))
                ->setName($this->trans('ID', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'id_treatment',
                    'sortable' => true,
                ])
            )
            ->add((new DataColumn('name'))
                ->setName($this->trans('Name', [], 'Modules.Salusperaquam.Admin'))
                ->setOptions([
                    'field' => 'name',
                ])
            )
            ->add((new DataColumn('code'))
                ->setName($this->trans('Code', [], 'Modules.Salusperaquam.Admin'))
                ->setOptions([
                    'field' => 'code',
                ])
            )
            ->add((new DataColumn('price'))
                ->setName($this->trans('Price', [], 'Modules.Salusperaquam.Admin'))
                ->setOptions([
                    'field' => 'price',
                ])
            )
            ->add((new DataColumn('product'))
                ->setName($this->trans('Product', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'product_name',
                    'sortable' => false,
                ])
            )
            ->add((new DataColumn('product_attribute'))
                ->setName($this->trans('Attribute', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'product_attribute_name',
                    'sortable' => false,
                ])
            )
            ->add((new ToggleColumn('active'))
                ->setName($this->trans('Enabled', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'active',
                    'primary_field' => 'id_treatment',
                    'route' => 'flavioski_salusperaquam_treatment_toggle_status',
                    'route_param_name' => 'treatmentId',
                ])
            )
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans('Edit', [], 'Admin.Actions'))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'flavioski_salusperaquam_treatment_edit',
                                'route_param_name' => 'treatmentId',
                                'route_param_field' => 'id_treatment',
                                'clickable_row' => true,
                            ])
                        )
                        ->add((new SubmitRowAction('delete'))
                            ->setName($this->trans('Delete', [], 'Admin.Actions'))
                            ->setIcon('delete')
                            ->setOptions([
                                'method' => 'DELETE',
                                'route' => 'flavioski_salusperaquam_treatment_delete',
                                'route_param_name' => 'treatmentId',
                                'route_param_field' => 'id_treatment',
                                'confirm_message' => $this->trans(
                                    'Delete selected item?',
                                    [],
                                    'Admin.Notifications.Warning'
                                ),
                            ])
                        ),
                ])
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_treatment', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('ID', [], 'Admin.Global'),
                    ],
                ])
                ->setAssociatedColumn('id_treatment')
            )
            ->add((new Filter('name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Name', [], 'Modules.Salusperaquam.Admin'),
                    ],
                ])
                ->setAssociatedColumn('name')
            )
            ->add((new Filter('price', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Price', [], 'Modules.Salusperaquam.Admin'),
                    ],
                ])
                ->setAssociatedColumn('price')
            )
            ->add((new Filter('product_name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search name', [], 'Admin.Actions'),
                    ],
                ])
                ->setAssociatedColumn('product')
            )
            ->add((new Filter('product_attribute_name', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Search name', [], 'Admin.Actions'),
                    ],
                ])
                ->setAssociatedColumn('product_attribute')
            )
            ->add((new Filter('active', YesAndNoChoiceType::class))
                ->setTypeOptions([
                    'required' => false,
                    'choice_translation_domain' => false,
                ])
                ->setAssociatedColumn('active')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'attr' => [
                        'data-url' => $this->resetActionUrl,
                        'data-redirect' => $this->redirectionUr,
                    ],
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'flavioski_salusperaquam_treatment_index',
                ])
                ->setAssociatedColumn('actions')
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getBulkActions()
    {
        return (new BulkActionCollection())
            ->add((new SubmitBulkAction('delete_bulk'))
                ->setName($this->trans('Delete selected', [], 'Admin.Actions'))
                ->setOptions([
                    'submit_route' => 'flavioski_salusperaquam_treatment_bulk_delete',
                    'confirm_message' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning'),
                ])
            )
            ;
    }
}
