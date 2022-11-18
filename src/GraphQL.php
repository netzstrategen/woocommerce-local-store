<?php

namespace Netzstrategen\WooCommerceLocalStore;

/**
 * GraphQL related functionalities.
 */
class GraphQL {

  /**
   * @implements graphql_register_types
   */
  public static function graphql_register_types() {
    self::registerLocalStoreStockStatusEnum();
    self::registerLocalStockObjectType();
    self::registerLocalStockField('Product');
    self::registerLocalStockField('ProductVariation');
  }

  /**
   * Registers local stock status enum.
   */
  public static function registerLocalStoreStockStatusEnum() {

    register_graphql_enum_type(
    'LocalStoreStockStatus',
    [
      'description' => __('Local stock status enumeration', Plugin::L10N),
      'values' => [
        'high' => ['value' => 'high'],
        'low' => ['value' => 'low'],
        'none' => ['value' => 'none'],
      ],
    ]
    );
  }

  /**
   * Registers localStock object type.
   */
  public static function registerLocalStockObjectType() {
    register_graphql_object_type(
      'localStock',
      [
        'description' => __('Local stock', Plugin::L10N),
        'fields' => [
          'label' => [
            'type' => 'String',
            'description' => __('Store name', Plugin::L10N),
          ],
          'showroom' => [
            'type' => 'LocalStoreStockStatus',
            'description' => __('Showroom', Plugin::L10N),
          ],
          'warehouse' => [
            'type' => 'LocalStoreStockStatus',
            'description' => __('Warehouse', Plugin::L10N),
          ],
          'online' => [
            'type' => 'LocalStoreStockStatus',
            'description' => __('Online', Plugin::L10N),
          ],
        ],
      ]
    );
  }

  /**
   * Registers localStock field.
   *
   * @param string $type_name
   *   The type name where to register the field.
   */
  public static function registerLocalStockField(string $type_name) {
    register_graphql_field($type_name, 'localStock', [
      'description' => __('Local stocks availability', Plugin::L10N),
      'type' => ['list_of' => 'localStock'],
      'resolve' => function ($source) {
        $product = $source->as_WC_Data();
        $local_stock = [];

        if (Product::isCategoryExcluded()) {
          return $local_stock;
        }

        if ($local_stock_raw_data = Product::getStockByStore($product)) {
          foreach ($local_stock_raw_data as $key => $value) {
            $local_stock[] = [
              'label' => $key ?? '',
              'showroom' => $value['showroom'] ?? '',
              'warehouse' => $value['warehouse'] ?? '',
              'online' => $value['online'] ?? '',
            ];
          }
        }

        return $local_stock;
      }
    ]);
  }

}
