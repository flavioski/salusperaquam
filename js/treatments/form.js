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
  // problem: we have many "select" on page with follow ids in the form. Example:
  //    #treatment_treatment_rates_1_id_product
  //    #treatment_treatment_rates_2_id_product
  //    #treatment_treatment_rates_3_id_product
  //    #treatment_treatment_rates_4_id_product
  //    ...
  // solution: we want to obtain the id of select (with a regex) that is being selected, on this moment, and put the value on ProductAttributeSelectionToggler function
  $('select[id^="treatment_treatment_rates_"][id$="_id_product"]').each(function() {
    const treatmentRateId = $(this).attr('id').match(/\d+/)[0];
    new ProductAttributeSelectionToggler(
      treatmentFormMap.treatmentProductSelect(treatmentRateId),
      treatmentFormMap.treatmentProductAttributeSelect(treatmentRateId),
      treatmentFormMap.treatmentProductAttributeBlock(treatmentRateId)
    );
  });
});
