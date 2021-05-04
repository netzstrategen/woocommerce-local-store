<div class="stock-table">
  <button class="stock-table__toggle" data-stock-table-toggle aria-expanded="false">
    <span class="map-ico"></span>
    <span><?= __('View branch availability', 'shop'); ?></span>
    <span class="arrow-ico" data-stock-arrow></span>
  </button>
  <table class="stock-table__table" data-stock-table aria-visible="false">
    <thead>
      <tr>
        <th class="column-1"></th>
        <th class="column-2"><?= __('In Show Room', 'shop') ?></th>
        <th class="column-3"><?= __('In Stock', 'shop') ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      extract($args);
      $i = 0;
      foreach ($locations as $location): ?>
        <tr>
          <th><?= $location; ?></th>
          <?php foreach ($types as $type): ?>
            <td>
              <?= $stocks[$i]; ?>
              <?php $i++ ?>
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
