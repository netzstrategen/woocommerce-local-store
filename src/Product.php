<?php

namespace Netzstrategen\MultiStock;

/**
 * WooCommerce Product related functionalities.
 */
class Product {

  /**
   * Adds custom fields to WooCommerce simple products.
   *
   * @implements woocommerce_product_options_inventory_product_data
   */
  public static function woocommerce_product_options_inventory_product_data() {
    if ($warehouses = Config::get('warehouses')) {
      static::renderSimpleProductsCustomFields($warehouses, 'Warehouses', 'warehouse');
    }

    if ($showrooms = Config::get('showrooms')) {
      static::renderSimpleProductsCustomFields($showrooms, 'Showrooms', 'showroom');
    }
  }

  /**
   * Renders simple product custom fields.
   *
   * @param array $fields
   *   Fields to render.
   * @param string $sectionTitle
   *   Title of the fields section.
   * @param string $fieldsIdPrefix
   *   Prefix used to identify the fields.
   */
  protected static function renderSimpleProductsCustomFields($fields, $sectionTitle, $fieldsIdPrefix) {
    echo '<div class="options_group">';
    echo '<h3 style="padding-left: 10px;">' . $sectionTitle . '</h3>';
    foreach ($fields as $field) {
      woocommerce_wp_text_input([
        'id' => sprintf('_%s_%s_%s', Plugin::PREFIX, $fieldsIdPrefix, $field['id']),
        'data_type' => 'stock',
        'type' => 'number',
        'label' => $field['name'],
      ]);
    }
    echo '</div>';
  }

  /**
   * Adds custom fields to WooCommerce products variations.
   *
   * @implements woocommerce_product_after_variable_attributes
   */
  public static function woocommerce_product_after_variable_attributes($loop, $variationData, $variation) {
    if ($warehouses = Config::get('warehouses')) {
      static::renderProductVariationsCustomFields($warehouses, 'Warehouses', 'warehouse', $loop, $variation->ID);
    }

    if ($showrooms = Config::get('showrooms')) {
      static::renderProductVariationsCustomFields($showrooms, 'Showrooms', 'showroom', $loop, $variation->ID);
    }
  }

  /**
   * Renders product variations custom fields.
   *
   * @param array $fields
   *   Fields to render.
   * @param string $sectionTitle
   *   Title of the fields section.
   * @param string $fieldsIdPrefix
   *   Prefix used to identify the fields.
   * @param int $loop
   *   The position in variations loop.
   * @param int $variationId
   *   The ID of the product variation.
   */
  protected static function renderProductVariationsCustomFields($fields, $sectionTitle, $fieldsIdPrefix, $loop, $variationId) {
    echo '<div class="options_group">';
    echo '<h3 style="padding-left: 0 !important;">' . $sectionTitle . '</h3>';
    foreach ($fields as $field) {
      $fieldId = sprintf('_%s_%s_%s', Plugin::PREFIX, $fieldsIdPrefix, $field['id']);
      woocommerce_wp_text_input([
        'wrapper_class' => 'form-row',
        'id' => $fieldId . '[' . $loop . ']',
        'data_type' => 'stock',
        'type' => 'number',
        'label' => $field['name'],
        'value' => get_post_meta($variationId, $fieldId, TRUE),
      ]);
    }
    echo '</div>';
  }

  /**
   * Processes simple product meta.
   *
   * @implements woocommerce_process_product_meta
   */
  public static function woocommerce_process_product_meta($postId) {
    if ($warehouses = Config::get('warehouses')) {
      static::saveSimpleProductCustomFields($warehouses, 'warehouse', $postId);
    }

    if ($showrooms = Config::get('showrooms')) {
      static::saveSimpleProductCustomFields($showrooms, 'showroom', $postId);
    }
  }

  /**
   * Saves custom fields for simple products.
   *
   * @param array $fields
   *   Fields to render.
   * @param string $fieldsIdPrefix
   *   Prefix used to identify the fields.
   * @param string $postId
   *   The WordPress Post ID.
   */
  protected static function saveSimpleProductCustomFields($fields, $fieldsIdPrefix, $postId) {
    foreach ($fields as $field) {
      $field = sprintf('_%s_%s_%s', Plugin::PREFIX, $fieldsIdPrefix, $field['id']);
      if (isset($_POST[$field])) {
        if (!is_array($_POST[$field]) && $_POST[$field] > 0) {
          update_post_meta($postId, $field, $_POST[$field]);
        }
        else {
          delete_post_meta($postId, $field);
        }
      }
    }
  }

  /**
   * Processes products variation meta.
   *
   * @implements woocommerce_save_product_variation
   */
  public static function woocommerce_save_product_variation($postId, $loop) {
    if (!isset($_POST['variable_post_id'])) {
      return;
    }
    $variationId = $_POST['variable_post_id'][$loop];

    if ($warehouses = Config::get('warehouses')) {
      static::saveProductVariationsCustomFields($warehouses, 'warehouse', $loop, $variationId);
    }

    if ($showrooms = Config::get('showrooms')) {
      static::saveProductVariationsCustomFields($showrooms, 'showroom', $loop, $variationId);
    }
  }

  /**
   * Saves custom fields for products variations.
   *
   * @param array $fields
   *   Fields to render.
   * @param string $fieldsIdPrefix
   *   Prefix used to identify the fields.
   * @param int $loop
   *   The position in variations loop.
   * @param int $variationId
   *   The ID of the product variation.
   */
  protected static function saveProductVariationsCustomFields($fields, $fieldsIdPrefix, $loop, $variationId) {
    foreach ($fields as $field) {
      $field = sprintf('_%s_%s_%s', Plugin::PREFIX, $fieldsIdPrefix, $field['id']);
      if (isset($_POST[$field])) {
        if ($_POST[$field][$loop]) {
          update_post_meta($variationId, $field, $_POST[$field][$loop] > 0);
        }
        else {
          delete_post_meta($variationId, $field);
        }
      }
    }
  }

}
