window.addEventListener('load', () => {
  document.querySelectorAll('[data-stock-table-toggle]').forEach((trigger) =>
    trigger.addEventListener('click', () => {
      const table = document.querySelector('[data-stock-table]');
      const arrow = document.querySelector('[data-stock-arrow]');
      if (!table && !arrow) {
        return false;
      }

      const elems = [trigger, table, arrow];

      elems.forEach((elem) => {
        if (elem !== arrow) {
          elem.classList.toggle('expanded');
        }
        else if (elem === arrow) {
          elem.classList.toggle('up-arrow');
        }
      });

      trigger.setAttribute('aria-expanded',
        trigger.classList.contains('expanded') ? 'true' : 'false');
      table.setAttribute('aria-visible',
        table.classList.contains('expanded') ? 'true' : 'false');

      return false;
    })
  );
});

/* global jQuery */
(function ($) {
  /**
   * Updates the stock levels table.
   *
   * @param {jQuery} $context
   * @param {array} stockLevels
   */
  function updateStockLevels($context, stockLevels) {
    $.each(stockLevels, (name, types) => {
      $.each(types, (type, level) => {
        $('[data-location="' + name + '"][data-type="' + type + '"] > span')
          .attr('class', 'stock-level stock-level--' + level);
      });
    });
  }

  /* global document */
  $(document)
    .on('show_variation', '.single_variation_wrap', function (event, variation) {
      const $context = $(this).closest('.product-summary').find('> .product_meta');
      updateStockLevels($context, variation['stock_levels']);
    })
    .on('hide_variation, reset_data', function (event) {
      const $context = $(event.target).closest('.product-summary').find('> .product_meta');
      updateStockLevels($context, $('[data-stock-table]', $context).data('stock-table'));
    });
}(jQuery));

/* global jQuery */
(function ($) {
  const html = document.querySelector('html');
  const productType = html.getAttribute('data-page-type');

  if (productType === 'Product | Variable') {
    let stockShow = true;
    /* global document */
    $(document)
      .on('show_variation', '.single_variation_wrap', function (event, variation) {
        const stockTable = document.querySelector('[data-stock-show]');
        if (stockShow === false) {
          stockShow = true;
          stockTable.classList.toggle('hide');
        }
      })
      .on('hide_variation, reset_data', function (event) {
        const stockTable = document.querySelector('[data-stock-show]');
        if (stockShow === true) {
          stockShow = false;
          stockTable.classList.toggle('hide');
        }
      });
  }
}(jQuery));
