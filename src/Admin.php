<?php

namespace Netzstrategen\MultiStock;

/**
 * Administrative back-end functionality.
 */
class Admin {

  /**
   * Plugin backend initialization method.
   *
   * @implements admin_init
   */
  public static function init() {
    // Adds custom fields to WooCommerce simple products.
    add_action('woocommerce_product_options_inventory_product_data', __NAMESPACE__ . '\Product::woocommerce_product_options_inventory_product_data');
    // Adds custom fields to WooCommerce products variations.
    add_action('woocommerce_product_after_variable_attributes', __NAMESPACE__ . '\Product::woocommerce_product_after_variable_attributes', 10, 3);
  }

}
