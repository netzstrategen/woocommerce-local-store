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
    self::registerLocalStockObjectType();
    self::registerLocalStockField('product');
    self::registerLocalStockField('ProductVariation');
  }

  /**
   * Registers localStock object type.
   */
  public static function registerLocalStockObjectType():void {

    register_graphql_object_type(
      'localStock',
      [
        'description' => __('Local stock', Plugin::L10N),
        'fields'      => [
          'storeName' => [
            'type'        => 'String',
            'description' => __('Store name', Plugin::L10N),
          ],
          'showroom'  => [
            'type'        => 'String',
            'description' => __('Showroom', Plugin::L10N),
          ],
          'warehouse'       => [
            'type'        => 'String',
            'description' => __('Warehouse', Plugin::L10N),
          ],
          'online'       => [
            'type'        => 'String',
            'description' => __('Online', Plugin::L10N),
          ],
        ],
      ]
    );
  }

  /**
   * Registers localStock field.
   */
  public static function registerLocalStockField($type_name):void {
    register_graphql_field(
      $type_name,
      'localStock',
      [
        'description' => __('Local stocks availability', Plugin::L10N),
        'type'        => ['list_of' => 'localStock'],
        'resolve'     => function($source) {
          $product = $source->as_WC_Data();
          $localStock = [];

          if (Product::is_category_excluded()) {
            return $localStock;
          }

          if ($local_stock_raw_data = Product::getStockByStore($product)) {
            foreach($local_stock_raw_data as $key => $value) {
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
