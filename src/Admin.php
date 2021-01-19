<?php

namespace Netzstrategen\WooCommerceLocalStore;

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
    add_action('woocommerce_product_options_stock_fields', __NAMESPACE__ . '\Product::woocommerce_product_options_stock_fields');
    // Adds custom fields to WooCommerce products variations.
    add_action('woocommerce_product_after_variable_attributes', __NAMESPACE__ . '\Product::woocommerce_product_after_variable_attributes', 10, 3);
  }

}
