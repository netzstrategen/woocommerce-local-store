<?php

namespace Netzstrategen\WooCommerceLocalStore;

?>
<div class="stock-table">
  <button class="stock-table__toggle" data-stock-table-toggle aria-expanded="false">
    <span class="map-ico"></span>
    <span><?= __('View branch availability', Plugin::L10N); ?></span>
    <span class="arrow-ico" data-stock-arrow></span>
  </button>
  <table class="stock-table__table" data-stock-table aria-visible="false">
    <thead>
      <tr>
        <th class="column-1">&nbsp;</th>
        <th class="column-2"><?= __('in showroom', Plugin::L10N) ?></th>
        <th class="column-3"><?= __('in stock', Plugin::L10N) ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($stocks as $location => $types): ?>
        <tr>
          <th><?= $location ?></th>
          <?php foreach ($types as $stock): ?>
            <td><?= $stock ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
