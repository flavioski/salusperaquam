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

import Grid from '@components/grid/grid';
import ReloadListActionExtension from '@components/grid/extension/reload-list-extension';
import AsyncToggleColumnExtension from '@components/grid/extension/column/common/async-toggle-column-extension';
import ExportToSqlManagerExtension from '@components/grid/extension/export-to-sql-manager-extension';
import FiltersResetExtension from '@components/grid/extension/filters-reset-extension';
import SortingExtension from '@components/grid/extension/sorting-extension';
import LinkRowActionExtension from '@components/grid/extension/link-row-action-extension';
import SubmitGridExtension from '@components/grid/extension/submit-grid-action-extension';
import SubmitBulkExtension from '@components/grid/extension/submit-bulk-action-extension';
import BulkActionCheckboxExtension from '@components/grid/extension/bulk-action-checkbox-extension';
import SubmitRowActionExtension from '@components/grid/extension/action/row/submit-row-action-extension';

const $ = window.$;

$(() => {
  const treatmentsGrid = new Grid('treatment');

  treatmentsGrid.addExtension(new ReloadListActionExtension());
  treatmentsGrid.addExtension(new AsyncToggleColumnExtension());
  treatmentsGrid.addExtension(new ExportToSqlManagerExtension());
  treatmentsGrid.addExtension(new FiltersResetExtension());
  treatmentsGrid.addExtension(new SortingExtension());
  treatmentsGrid.addExtension(new LinkRowActionExtension());
  treatmentsGrid.addExtension(new SubmitGridExtension());
  treatmentsGrid.addExtension(new SubmitBulkExtension());
  treatmentsGrid.addExtension(new BulkActionCheckboxExtension());
  treatmentsGrid.addExtension(new SubmitRowActionExtension());
});
