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
    // Displays a notice if woocommerce is not installed and active.
    add_action('admin_notices', __CLASS__ . '::admin_notices');

    // Adds custom fields to WooCommerce simple products.
    add_action('woocommerce_product_options_stock_fields', __NAMESPACE__ . '\Product::woocommerce_product_options_stock_fields');
    // Adds custom fields to WooCommerce products variations.
    add_action('woocommerce_product_after_variable_attributes', __NAMESPACE__ . '\Product::woocommerce_product_after_variable_attributes', 10, 3);
  }

  /**
   * Displays a notice if WooCommerce is not installed and active.
   *
   * @implements admin_notices
   */
  public static function admin_notices() {
    if (!class_exists('WooCommerce')) {
      ?>
        <div class="error below-h3">
          <p>
            <strong><?= __('Multi Stock plugin requires WooCommerce to be installed and active.', Plugin::L10N); ?></strong>
          </p>
        </div>
      <?php
    }
  }

}
