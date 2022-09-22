<?php

namespace Netzstrategen\WooCommerceLocalStore;

/**
 * Main front-end functionality.
 */
class Plugin {

  /**
   * Prefix for naming.
   *
   * @var string
   */
  const PREFIX = 'woocommerce-local-store';

  /**
   * Gettext localization domain.
   *
   * @var string
   */
  const L10N = self::PREFIX;

  /**
   * @var string
   */
  private static $baseUrl;

  /**
   * Plugin initialization method.
   *
   * @implements init
   */
  public static function init() {
    // Adds stock fields to product meta fields.
    add_action('woocommerce_process_product_meta', __NAMESPACE__ . '\Product::woocommerce_process_product_meta');
    add_action('woocommerce_save_product_variation', __NAMESPACE__ . '\Product::woocommerce_save_product_variation', 10, 2);

    // GraphQL support.
    add_action('graphql_register_types', __NAMESPACE__ . '\GraphQL::graphql_register_types');

    if (is_admin()) {
      return;
    }

    add_filter('woocommerce_available_variation', __NAMESPACE__ . '\Product::woocommerce_available_variation', 10, 3);
    // Displays the shop stock-status component on the front-end.
    add_action('woocommerce_product_meta_end', __NAMESPACE__ . '\Product::display_store_stock_status_block');

    // Enqueues styles and scripts.
    add_action('wp_enqueue_scripts', __CLASS__ . '::enqueueAssets');
  }

  /**
   * Enqueues styles and scripts.
   *
   * @implements wp_enqueue_scripts
   */
  public static function enqueueAssets() {
    if (!is_product() || has_term(Product::CATEGORY_EXCLUDED, 'product_cat')) {
      return;
    }

    wp_enqueue_style('woocommerce-local-store/custom', static::getBaseUrl() . '/dist/styles/main.min.css', FALSE);
    wp_enqueue_script('woocommerce-local-store/custom', static::getBaseUrl() . '/dist/scripts/main.min.js', ['jquery'], FALSE, TRUE);
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
   * The base URL path to this plugin's folder.
   *
   * Uses plugins_url() instead of plugin_dir_url() to avoid a trailing slash.
   */
  public static function getBaseUrl() {
    if (!isset(static::$baseUrl)) {
      static::$baseUrl = plugins_url('', static::getBasePath() . '/plugin.php');
    }
    return static::$baseUrl;
  }

  /**
   * Loads the plugin textdomain.
   */
  public static function loadTextdomain() {
    load_plugin_textdomain(static::L10N, FALSE, static::L10N . '/languages/');
  }

  /**
   * Renders a given plugin template, optionally overridden by the theme.
   *
   * WordPress offers no built-in function to allow plugins to render templates
   * with custom variables, respecting possibly existing theme template overrides.
   * Inspired by Drupal (5-7).
   *
   * @param array $template_subpathnames
   *   An prioritized list of template (sub)pathnames within the plugin/theme to
   *   discover; the first existing wins.
   * @param array $variables
   *   An associative array of template variables to provide to the template.
   *
   * @throws \InvalidArgumentException
   *   If none of the $template_subpathnames files exist in the plugin itself.
   */
  public static function renderTemplate(array $template_subpathnames, array $variables = []) {
    $template_pathname = locate_template($template_subpathnames, FALSE, FALSE);
    extract($variables, EXTR_SKIP | EXTR_REFS);
    if ($template_pathname !== '') {
      include $template_pathname;
    }
    else {
      while ($template_pathname = current($template_subpathnames)) {
        if (file_exists($template_pathname = static::getBasePath() . '/' . $template_pathname)) {
          include $template_pathname;
          return;
        }
        next($template_subpathnames);
      }
      throw new \InvalidArgumentException("Missing template '$template_pathname'");
    }
  }

}
