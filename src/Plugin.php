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
    // Adds stock fields to product meta fields.
    add_action('woocommerce_process_product_meta', __NAMESPACE__ . '\Product::woocommerce_process_product_meta');
    add_action('woocommerce_save_product_variation', __NAMESPACE__ . '\Product::woocommerce_save_product_variation', 10, 2);

    if (is_admin()) {
      return;
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
