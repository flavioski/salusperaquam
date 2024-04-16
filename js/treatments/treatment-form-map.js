
export default {
  treatmentProductSelect: (treatmentRateId) =>
    `#treatment_treatment_rates_${treatmentRateId}_id_product`,
  treatmentProductAttributeSelect: (treatmentRateId) =>
    `#treatment_treatment_rates_${treatmentRateId}_id_product_attribute`,
  treatmentProductAttributeBlock: (treatmentRateId) =>
    `#treatment_treatment_rates_${treatmentRateId}` + ' > .js-treatment-product-attribute-select',
};
