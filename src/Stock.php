<?php

namespace Netzstrategen\WooCommerceLocalStore;

class Stock {

  function checkStoreStockStatus($stock_level) {
    global $woocommerce;
    // Set threshold.
    $low_stock_level = isset($woocommerce->low_stock_amount)
      ? get_option('low_stock_amount')
      : get_option('woocommerce_notify_low_stock_amount');
    // Set stock status.
    if ($stock_level > $low_stock_level) {
      $status = 'high';
      $text = __('High stock', Plugin::L10N);
    }
    elseif ($stock_level <= 0) {
      $status = 'low';
      $text = __('Low stock', Plugin::L10N);
    }
    else {
      $status = 'medium';
      $text = __('Medium stock', Plugin::L10N);
    }

    return '<span class="stock-level stock-level--' . $status . '"><span class="stock-level text">' . $text . '</span></span>';

  }

}
