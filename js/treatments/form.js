/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

import TranslatableInput from '@components/translatable-input';
import ProductAttributeSelectionToggler from './components/product-attribute-selection-toggler';
import treatmentFormMap from './treatment-form-map';

const $ = window.$;

$(() => {
  new TranslatableInput();
  new ProductAttributeSelectionToggler(
    treatmentFormMap.treatmentProductSelect,
    treatmentFormMap.treatmentProductAttributeSelect,
    treatmentFormMap.treatmentProductAttributeBlock
  );
});
