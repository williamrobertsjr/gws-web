document.addEventListener('DOMContentLoaded', function() {
  var elem = document.querySelector('.tiles-grid');
  var iso = new Isotope(elem, {
      itemSelector: '.grid-item',
      layoutMode: 'fitRows',
      fitRows: {
          gutter: 0
      }
  });

  // Use Element.matches for compatibility
  function matchesSelector(element, selector) {
      return element.matches(selector);
  }

  const filtersElem = document.querySelector('.category-filters');
  filtersElem.addEventListener('click', function(event) {
      if (!matchesSelector(event.target, 'button')) {
          return;
      }
      var filterValue = event.target.getAttribute('data-filter');
      // If filterValue is 'all', use '*', otherwise use '.' to build a class selector
      filterValue = filterValue === 'all' ? '*' : '.' + filterValue;
      iso.arrange({ filter: filterValue });
  });

  var buttonGroups = document.querySelectorAll('.category-filters');
  for (var i = 0, len = buttonGroups.length; i < len; i++) {
      var buttonGroup = buttonGroups[i];
      radioButtonGroup(buttonGroup);
  }

  function radioButtonGroup(buttonGroup) {
      buttonGroup.addEventListener('click', function(event) {
          if (!matchesSelector(event.target, 'button')) {
              return;
          }
          var checkedButton = buttonGroup.querySelector('.is-checked');
          if (checkedButton) {
              checkedButton.classList.remove('is-checked');
          }
          event.target.classList.add('is-checked');
      });
  }
});
