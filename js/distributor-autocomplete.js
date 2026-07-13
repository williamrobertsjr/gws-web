// js/distributor-autocomplete.js

(function () {
  const FIELD_ID = '#input_32_11';
  const REST_URL = '/wp-json/gws/v1/distributors';
  const MIN_CHARS = 3;
  const SALES_EMAIL = 'sales@gwstoolgroup.com';

  function init() {
    const original = document.querySelector(FIELD_ID);
    if (!original) return;

    // Hide the original GF input
    original.style.display = 'none';
    original.removeAttribute('required');

    // Build typeahead wrapper
    const wrapper = document.createElement('div');
    wrapper.className = 'gws-typeahead-wrapper';
    wrapper.style.position = 'relative';

    // Visible search input
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.id = 'gws_company_search';
    searchInput.className = 'large';
    searchInput.placeholder = 'Type at least 3 characters to search...';
    searchInput.autocomplete = 'off';
    searchInput.setAttribute('aria-autocomplete', 'list');
    searchInput.setAttribute('aria-expanded', 'false');

    // Dropdown list
    const dropdown = document.createElement('ul');
    dropdown.id = 'gws-distributor-dropdown';
    dropdown.style.cssText = `
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: #fff;
      border: 1px solid #ccc;
      border-top: none;
      max-height: 220px;
      overflow-y: auto;
      margin: 0;
      padding: 0;
      list-style: none;
      z-index: 9999;
      display: none;
    `;

    // Helper text
    // const helperText = document.createElement('p');
    // helperText.className = 'gws-distributor-helper';
    // helperText.innerHTML = `Don't see your company? Email <a href="mailto:${SALES_EMAIL}">${SALES_EMAIL}</a> to get added.`;
    // helperText.style.cssText = 'margin-top: 6px; font-size: 0.85em; color: #666;';

    wrapper.appendChild(searchInput);
    wrapper.appendChild(dropdown);
    original.parentNode.insertBefore(wrapper, original);
    // wrapper.appendChild(helperText);

    // State
    let debounceTimer = null;
    let selectedValue = null;
    let activeIndex = -1;

    // Search
    searchInput.addEventListener('input', function () {
      const query = this.value.trim();
      selectedValue = null;
      original.value = '';

      clearTimeout(debounceTimer);

      if (query.length < MIN_CHARS) {
        closeDropdown();
        return;
      }

      debounceTimer = setTimeout(() => fetchCompanies(query), 250);
    });

    // Fetch from REST
    function fetchCompanies(query) {
      fetch(`${REST_URL}?s=${encodeURIComponent(query)}`)
        .then(r => r.json())
        .then(data => renderDropdown(data))
        .catch(() => closeDropdown());
    }

    // Render dropdown
    function renderDropdown(results) {
      dropdown.innerHTML = '';
      activeIndex = -1;

      if (!results.length) {
        const li = document.createElement('li');
        li.textContent = 'No results found.';
        li.style.cssText = 'padding: 8px 12px; color: #999; font-style: italic;';
        dropdown.appendChild(li);
        openDropdown();
        return;
      }

      results.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item.company_name;
        li.dataset.id = item.id;
        li.style.cssText = 'padding: 8px 12px; cursor: pointer;';

        li.addEventListener('mouseenter', () => {
          activeIndex = Array.from(dropdown.querySelectorAll('li[data-id]')).indexOf(li);
          highlightItem(activeIndex);
        });
        li.addEventListener('mouseleave', () => li.style.background = '');
        li.addEventListener('mousedown', (e) => {
          e.preventDefault(); // prevent blur before click registers
          selectCompany(item.company_name);
        });

        dropdown.appendChild(li);
      });

      openDropdown();
    }

    // Select a company
    function selectCompany(name) {
      selectedValue = name;
      searchInput.value = name;
      original.value = name;
      closeDropdown();
    }

    // Keyboard navigation
    function highlightItem(index) {
      const items = Array.from(dropdown.querySelectorAll('li[data-id]'));
      items.forEach((li, i) => {
        li.style.background = i === index ? '#f0f0f0' : '';
      });
      if (items[index]) items[index].scrollIntoView({ block: 'nearest' });
    }

    searchInput.addEventListener('keydown', function (e) {
      if (dropdown.style.display === 'none') return;
      const items = Array.from(dropdown.querySelectorAll('li[data-id]'));

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeIndex = Math.min(activeIndex + 1, items.length - 1);
        highlightItem(activeIndex);
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeIndex = Math.max(activeIndex - 1, -1);
        highlightItem(activeIndex);
      } else if (e.key === 'Enter') {
        e.preventDefault();
        if (activeIndex >= 0 && items[activeIndex]) {
          selectCompany(items[activeIndex].textContent);
        }
      } else if (e.key === 'Escape') {
        closeDropdown();
      }
    });

    // Close on blur if nothing selected
    searchInput.addEventListener('blur', function () {
      setTimeout(() => {
        closeDropdown();
        // If they typed but didn't select, clear it
        if (!selectedValue) {
          searchInput.value = '';
          original.value = '';
        }
      }, 150);
    });

    function openDropdown() {
      dropdown.style.display = 'block';
      searchInput.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
      dropdown.style.display = 'none';
      searchInput.setAttribute('aria-expanded', 'false');
      activeIndex = -1;
    }
  }

  // GF fires this event when the form is ready
  document.addEventListener('DOMContentLoaded', init);
})();