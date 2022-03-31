/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

const {$} = window;

/**
 * Displays, fills or hides product attributes selection block depending on selected product.
 *
 * Usage:
 *
 * <!-- Product select must have unique identifier & url for states API -->
 * <select name="id_product" id="id_product" states-url="path/to/combinations/api">
 *   ...
 * </select>
 *
 * <!-- If selected product does not have combinations, then this block will be hidden -->
 * <div class="js-combination-selection-block">
 *   <select name="id_product_attribute">
 *     ...
 *   </select>
 * </div>
 *
 * In JS:
 *
 * new ProductAttributeSelectionToggler('#id_product', '#id_product_attribute', '.js-state-selection-block');
 */
export default class ProductAttributeSelectionToggler {
  constructor(productInputSelector, productAttributeSelector, combinationSelectionBlockSelector) {
    this.$combinationSelectionBlock = $(combinationSelectionBlockSelector);
    this.$productAttributeSelector = $(productAttributeSelector);
    this.$productInput = $(productInputSelector);

    this.$productInput.on('change', () => this.change());

    return {};
  }

  /**
   * Change combination selection
   *
   * @private
   */
  change() {
    const productId = this.$productInput.val();

    if (productId === '') {
      return;
    }
    $.get({
      url: this.$productInput.data('combinations-url'),
      dataType: 'json',
      data: {
        id_product: productId,
      },
    })
      .then((response) => {
        this.$productAttributeSelector.empty();

        Object.keys(response.combinations).forEach((value) => {
          this.$productAttributeSelector.append(
            $('<option></option>')
              .attr('value', response.combinations[value])
              .text(value)
          );
        });

        this.toggle();
      })
      .catch((response) => {
        if (typeof response.responseJSON !== 'undefined') {
          window.showErrorMessage(response.responseJSON.message);
        }
      });
  }

  toggle() {
    this.$combinationSelectionBlock.toggleClass('d-none', !this.$productAttributeSelector.find('option').length > 0);
  }
}
