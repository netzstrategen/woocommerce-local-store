<?php

namespace Netzstrategen\MultiStock;

/**
 * Main front-end functionality.
 */
class Plugin {

  /**
   * Prefix for naming.
   *
   * @var string
   */
  const PREFIX = 'multi-stock';

  /**
   * Gettext localization domain.
   *
   * @var string
   */
  const L10N = self::PREFIX;

  /**
   * Plugin initialization method.
   *
   * @implements init
   */
  public static function init() {
    // Saves custom product meta fields.
    add_action('woocommerce_process_product_meta', __NAMESPACE__ . '\Product::woocommerce_process_product_meta');
    add_action('woocommerce_save_product_variation', __NAMESPACE__ . '\Product::woocommerce_save_product_variation', 10, 2);

    if (is_admin()) {
      return;
    }
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

  /**
   * The absolute filesystem base path of this plugin.
   *
   * @return string
   *   The plugin absolute filesystem base path.
   */
  public static function getBasePath() {
    return dirname(__DIR__);
  }

  /**
   * Loads the plugin textdomain.
   */
  public static function loadTextdomain() {
    load_plugin_textdomain(static::L10N, FALSE, static::L10N . '/languages/');
  }

}
