<?php

namespace Netzstrategen\WooCommerceLocalStore;

$labels = [
  'data-label-high' => __('Available', Plugin::L10N),
  'data-label-low' => __('Low stock', Plugin::L10N),
  'data-label-none' => __('Not available', Plugin::L10N),
];

?>
<div class="stock-table" data-stock-show>
  <button class="stock-table__toggle" data-stock-table-toggle aria-expanded="false">
    <span class="map-ico"></span>
    <span><?= __('View branch availability', Plugin::L10N); ?></span>
    <span class="arrow-ico" data-stock-arrow></span>
  </button>
  <table class="stock-table__table" data-stock-table="<?= esc_attr(json_encode($raw)) ?>" <?= wc_implode_html_attributes($labels) ?> aria-visible="false">
    <thead>
      <tr>
        <th class="column-1">&nbsp;</th>
        <th class="column-2"><?= __('Showroom', Plugin::L10N) ?></th>
        <th class="column-3"><?= __('Pickup', Plugin::L10N) ?></th>
        <th class="column-4"><?= __('Order', Plugin::L10N) ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($stocks as $location => $types): ?>
        <tr>
          <th><?= $location ?></th>
          <?php foreach ($types as $type => $stock): ?>
            <td data-location="<?= esc_attr($location) ?>" data-type="<?= $type ?>"><?= $stock ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
