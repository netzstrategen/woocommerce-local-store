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
    self::registerLocalStockStatusEnum();
    self::registerLocalStockObjectType();
    self::registerLocalStockField('product');
    self::registerLocalStockField('ProductVariation');
  }

  /**
   * Registers local stock status enum.
   */
  public static function registerLocalStockStatusEnum() {
    $values = [
      'HIGH' => ['value' => 'high'],
      'LOW' => ['value' => 'low'],
      'NONE' => ['value' => 'none'],
    ];

    register_graphql_enum_type(
    'LocalStockStatus',
    [
      'description' => __('Local stock status enumeration', Plugin::L10N),
      'values' => $values,
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
          'storeName' => [
            'type' => 'String',
            'description' => __('Store name', Plugin::L10N),
          ],
          'showroom' => [
            'type' => 'LocalStockStatus',
            'description' => __('Showroom', Plugin::L10N),
          ],
          'warehouse' => [
            'type' => 'LocalStockStatus',
            'description' => __('Warehouse', Plugin::L10N),
          ],
          'online' => [
            'type' => 'LocalStockStatus',
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
    register_graphql_field(
      $type_name,
      'localStock',
      [
        'description' => __('Local stocks availability', Plugin::L10N),
        'type' => ['list_of' => 'localStock'],
        'resolve' => function ($source) {
          $product = $source->as_WC_Data();
          $localStock = [];

          if (Product::isCategoryExcluded()) {
            return $localStock;
          }

          if ($local_stock_raw_data = Product::getStockByStore($product)) {
            foreach ($local_stock_raw_data as $key => $value) {
              $localStock[] = [
                'storeName' => $key ?? '',
                'showroom' => $value['showroom'] ?? '',
                'warehouse' => $value['warehouse'] ?? '',
                'online' => $value['online'] ?? '',
              ];
            }
          }

          return $localStock;
        }
      ]
    );
  }

}
