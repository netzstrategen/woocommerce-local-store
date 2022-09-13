<?php

namespace Netzstrategen\WooCommerceLocalStore;

class Stock {

  /**
   * Returns the stock level for a given product.
   */
  public static function getLevel(\WC_Product $product, int $stock, string $type): string {
    $low_stock_amount = wc_get_low_stock_amount($product);

    if ($stock > $low_stock_amount) {
      $level = 'high';
    }
    elseif ($stock <= 0) {
      $level = 'none';
    }
    else {
      $level = $type === 'warehouse' ? 'low' : 'high';
    }
    return $level;
  }

  public static function renderLevel(string $stock_level, string $type): string {
    if ($stock_level === 'high') {
      $text = __('Available', Plugin::L10N);
    }
    elseif ($stock_level === 'low') {
      $text = __('Low stock', Plugin::L10N);
    }
    elseif ($stock_level === 'high-asterisk') {
      $text = __('Low stock', Plugin::L10N);
    }
    else {
      $text = __('Not available', Plugin::L10N);
    }
    return '<span class="stock-level stock-level--' . $stock_level . '" title="' . $text . '"><span class="description">' . $text . '</span></span>';
  }

}
