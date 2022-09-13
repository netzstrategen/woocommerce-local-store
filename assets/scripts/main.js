window.addEventListener('load', () => {
  document.querySelectorAll('[data-stock-table-toggle]').forEach((trigger) =>
    trigger.addEventListener('click', (event) => {
      const tableWrapper = document.querySelector('[data-stock-table-wrapper]');
      const arrow = document.querySelector('[data-stock-arrow]');
      if (!tableWrapper && !arrow) {
        return false;
      }

      event.preventDefault();

      const elems = [trigger, tableWrapper, arrow];

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
      tableWrapper.setAttribute('aria-visible',
        tableWrapper.classList.contains('expanded') ? 'true' : 'false');

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
   * @param {Object} variation
   */
  function updateStockLevels($context, stockLevels, variation = null) {
    const $labels = $('[data-stock-table]');
    $.each(stockLevels, (name, types) => {
      $.each(types, (type, level) => {
        const availability = JSON.parse($('[data-location="' + name + '"][data-type="' + type + '"]')
          .attr('data-availability'));
        let existsInStock = false;

        if (availability.length && variation) {
          existsInStock = availability.indexOf(variation.variation_id) === -1;
        }

        if (existsInStock) {
          level = 'high';
          level += ' stock-level--asterisk';
        }

        $('[data-location="' + name + '"][data-type="' + type + '"] > span')
          .attr('class', 'stock-level stock-level--' + level)
          .attr('title', $labels.attr('data-label-' + level))
          .find('> .description').text($labels.attr('data-label-' + level));
      });
    });
  }

  /* global document */
  $(document)
    .on('show_variation', '.single_variation_wrap', function (event, variation) {
      const $context = $(this).closest('.product-summary').find('> .product_meta');
      updateStockLevels($context, variation['stock_levels'], variation);
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
        if (stockShow === false) {
          stockShow = true;
        }
      })
      .on('hide_variation, reset_data', function (event) {
        if (stockShow === true) {
          stockShow = false;
        }
      });
  }
}(jQuery));
