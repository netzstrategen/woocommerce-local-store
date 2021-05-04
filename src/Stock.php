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
    }
    elseif ($stock_level <= 0) {
      $status = 'low';
    }
    else {
      $status = 'medium';
    }

    return '<span class="stock-level stock-level--' . $status . '"><span class="stock-level text">' . ucfirst($status) . ' stock</span></span>';

  }

}
