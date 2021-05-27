<?php

namespace Netzstrategen\WooCommerceLocalStore;

class Stock {

  public static function renderStatus(\WC_Product $product, int $stock_level): string {
    $low_stock_level = wc_get_low_stock_amount($product);

    if ($stock_level > $low_stock_level) {
      $status = 'high';
      $text = __('High stock', Plugin::L10N);
    }
    elseif ($stock_level <= 0) {
      $status = 'none';
      $text = __('Not available', Plugin::L10N);
    }
    else {
      $status = 'low';
      $text = __('Low stock', Plugin::L10N);
    }

    return '<span class="stock-level stock-level--' . $status . '" title="' . $text . '"><span class="description">' . $text . '</span></span>';

  }

}
