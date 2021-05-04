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
