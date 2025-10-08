// tier-selector.js
// This script manages the tier selection for sales team and stores the selected tier in localStorage.
// It listens for changes on the tier selector dropdown and updates the localStorage accordingly.
// When the tier changes, it dispatches a custom event 'tierChanged' with the selected tier as detail.
// This allows other parts of the application to react to tier changes without directly coupling them.
// It also dispatches a custom event when the tier changes.
document.addEventListener('DOMContentLoaded', function () {
  const tierSelector = document.getElementById('tier-selector');
  
  if (!tierSelector) return;

  // Restore from localStorage
  const savedTier = localStorage.getItem('selectedTier');
  if (savedTier) {
    tierSelector.value = savedTier;
    dispatchTierChanged(savedTier);
  }

  tierSelector.addEventListener('change', function () {
    console.log('Tier Selector script loaded');
    const selectedTier = this.value;
    localStorage.setItem('selectedTier', selectedTier);
    dispatchTierChanged(selectedTier);
  });

  function dispatchTierChanged(tier) {
    const event = new CustomEvent('tierChanged', { detail: { tier } });
    document.dispatchEvent(event);

    // Persist tier for server-side pricing (SideCart, mini cart, etc.)
    document.cookie = `gws_selected_tier=${tier}; path=/; max-age=${60 * 60 * 24 * 30}`;

    // Refresh WooCommerce fragments so SideCart reflects new tier pricing
    if (window.jQuery && typeof jQuery === 'function') {
      jQuery(document.body).trigger('wc_fragment_refresh');
    }
  }
});
