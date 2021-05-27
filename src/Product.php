<?php

namespace Netzstrategen\WooCommerceLocalStore;

/**
 * WooCommerce Product related functionalities.
 */
class Product {

  /**
   * Adds custom fields to WooCommerce simple products.
   *
   * @implements woocommerce_product_options_stock_fields
   */
  public static function woocommerce_product_options_stock_fields() {
    if ($showrooms = Config::getStoreIdsByType('showroom')) {
      static::renderSimpleProductsCustomFields($showrooms, __('Stock in showrooms', Plugin::L10N));
    }
    if ($warehouses = Config::getStoreIdsByType('warehouse')) {
      static::renderSimpleProductsCustomFields($warehouses, __('Warehouse stock', Plugin::L10N));
    }
  }

  /**
   * Adds custom fields to WooCommerce products variations.
   *
   * @implements woocommerce_product_after_variable_attributes
   */
  public static function woocommerce_product_after_variable_attributes(int $loop, $variation_data, $variation) {
    if ($showrooms = Config::getStoreIdsByType('showroom')) {
      static::renderProductVariationsCustomFields($showrooms, __('Stock in showrooms', Plugin::L10N), $loop, $variation->ID);
    }
    if ($warehouses = Config::getStoreIdsByType('warehouse')) {
      static::renderProductVariationsCustomFields($warehouses, __('Warehouse stock', Plugin::L10N), $loop, $variation->ID);
    }
  }

  /**
   * Renders simple product custom fields.
   *
   * @param array $store_ids
   *   Fields to render.
   * @param string $section_title
   *   Title of the fields section.
   */
  protected static function renderSimpleProductsCustomFields(array $store_ids, string $section_title) {
    echo '<div class="options_group">';
    echo '<h4 style="padding-left: 10px;">' . $section_title . '</h4>';
    foreach ($store_ids as $store_id) {
      $store = Store::fromConfig($store_id);
      woocommerce_wp_text_input([
        'id' => sprintf('_%s_%s', Plugin::PREFIX, $store->getId()),
        'data_type' => 'stock',
        'type' => 'number',
        'label' => sprintf('%s (%s)', $store->getName(), $store->getId()),
        'value' => $store->getStock(get_the_ID()),
      ]);
    }
    echo '</div>';
  }

  /**
   * Renders product variations custom fields.
   *
   * @param array $store_ids
   *   Fields to render.
   * @param string $section_title
   *   Title of the fields section.
   * @param int $loop
   *   The position in variations loop.
   * @param int $variation_id
   *   The ID of the product variation.
   */
  protected static function renderProductVariationsCustomFields(array $store_ids, string $section_title, int $loop, int $variation_id) {
    echo '<div class="options_group">';
    echo '<h4 style="padding-left: 0 !important;">' . $section_title . '</h4>';
    foreach ($store_ids as $store_id) {
      $store = Store::fromConfig($store_id);
      $fieldId = sprintf('_%s_%s', Plugin::PREFIX, $store->getId());
      woocommerce_wp_text_input([
        'wrapper_class' => 'form-row',
        'id' => $fieldId . '[' . $loop . ']',
        'data_type' => 'stock',
        'type' => 'number',
        'label' => sprintf('%s (%s)', $store->getName(), $store->getId()),
        'value' => $store->getStock($variation_id),
      ]);
    }
    echo '</div>';
  }

  /**
   * Processes simple product meta.
   *
   * @implements woocommerce_process_product_meta
   */
  public static function woocommerce_process_product_meta($post_id) {
    static::saveSimpleProductCustomFields(Config::getAllStoreIds(), $post_id);
  }

  /**
   * Saves custom fields for simple products.
   *
   * @param array $store_ids
   *   Fields to render.
   * @param string $post_id
   *   The WordPress Post ID.
   */
  protected static function saveSimpleProductCustomFields(array $store_ids, int $post_id) {
    foreach ($store_ids as $store_id) {
      $store = Store::fromConfig($store_id);
      $field = sprintf('_%s_%s', Plugin::PREFIX, $store->getId());
      if (isset($_POST[$field])) {
        if (!is_array($_POST[$field]) && $_POST[$field] > 0) {
          $store->setStock($post_id, $_POST[$field]);
        }
        else {
          $store->deleteStock($post_id);
        }
      }
    }
  }

  /**
   * Processes products variation meta.
   *
   * @implements woocommerce_save_product_variation
   */
  public static function woocommerce_save_product_variation($post_id, $loop) {
    if (!isset($_POST['variable_post_id'])) {
      return;
    }
    $variation_id = $_POST['variable_post_id'][$loop];
    static::saveProductVariationsCustomFields(Config::getAllStoreIds(), $loop, $variation_id);
  }

  /**
   * Saves custom fields for products variations.
   *
   * @param array $store_ids
   *   Fields to render.
   * @param int $loop
   *   The position in variations loop.
   * @param int $variation_id
   *   The ID of the product variation.
   */
  protected static function saveProductVariationsCustomFields($store_ids, $loop, $variation_id) {
    foreach ($store_ids as $store_id) {
      $store = Store::fromConfig($store_id);
      $field = sprintf('_%s_%s', Plugin::PREFIX, $store->getId());
      if (isset($_POST[$field])) {
        if ($_POST[$field][$loop] > 0) {
          $store->setStock($variation_id, $_POST[$field][$loop]);
        }
        else {
          $store->deleteStock($variation_id);
        }
      }
    }
  }

  /**
   * Retrieves stock level information by store and type.
   */
  public static function getStockByStore(\WC_Product $product): array {
    $product_id = $product->get_id();

    $stores = Config::get();
    $stocks = [];
    foreach ($stores as $store) {
      if (!isset($stocks[$store['name']][$store['type']])) {
        // Ensure stable column order to simplify template logic.
        $stocks += [$store['name'] => []];
        $stocks[$store['name']] += ['showroom' => 0, 'warehouse' => 0];
      }
      $stocks[$store['name']][$store['type']] += Store::fromConfig($store['id'])->getStock($product_id);
    }
    foreach ($stocks as $name => $types) {
      foreach ($types as $type => $stock) {
        $stocks[$name][$type] = Stock::getLevel($product, $stocks[$name][$type] ?? 0);
      }
    }
    return $stocks;
  }

  /**
   * Adds stock information for local stores to variation data.
   *
   * @implements woocommerce_available_variation
   */
  public static function woocommerce_available_variation(array $variation_data, \WC_Product $parent, \WC_Product $variation): array {
    $variation_data['stock_levels'] = static::getStockByStore($variation);
    return $variation_data;
  }

  /**
   * Displays the shop stock-status component on the front-end.
   */
  public static function woocommerce_product_meta_start() {
    global $product;

    $stocks = static::getStockByStore($product);
    $raw = $stocks;
    foreach ($stocks as $name => $types) {
      foreach ($types as $type => $stock) {
        $stocks[$name][$type] = Stock::renderLevel($stocks[$name][$type]);
      }
    }
    Plugin::renderTemplate(['templates/store-stock.php'], [
      'raw' => $raw,
      'stocks' => $stocks,
    ]);
  }

}
