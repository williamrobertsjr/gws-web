document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.cart-qty-input').forEach(input => {
    let timer;

    const handler = () => {
      const cartKey = input.dataset.cartKey;
      const quantity = input.value;

      updateCartQuantity(cartKey, quantity).then(data => {
        if (data.success) {
          const currentTier = localStorage.getItem('selectedTier');
          if (currentTier) {
            updatePricesByTier(currentTier);
          }
        } else {
          console.warn('Failed to update cart quantity');
        }
      });
    };

    input.addEventListener('input', () => {
      clearTimeout(timer);
      timer = setTimeout(handler, 500);
    });

    input.addEventListener('change', () => {
      clearTimeout(timer);
      handler();
    });
  });

  const tierSelector = document.getElementById('tier-selector');

  // Restore selected tier from localStorage if it exists
  const savedTier = localStorage.getItem('selectedTier');
  if (tierSelector && savedTier) {
    tierSelector.value = savedTier;
    updatePricesByTier(savedTier);
  }

  if (tierSelector) {
    tierSelector.addEventListener('change', function () {
      const selectedTier = this.value;
      console.log('Tier changed:', selectedTier);

      localStorage.setItem('selectedTier', selectedTier);
      updatePricesByTier(selectedTier);
    });
  }
});

function updatePricesByTier(tier) {
  console.log('Calling updatePricesByTier...');
  fetch(`/wp-admin/admin-ajax.php?action=get_discounted_prices_by_tier&tier=${tier}`, {
    method: 'GET',
    credentials: 'same-origin'
  })
    .then(res => res.json())
    .then(response => {
      console.log('Full response:', response);
      const cartItems = response.data.data;
      const originalTotalHtml = response.data.original_total_html;
      const discountedTotalHtml = response.data.discounted_total_html;

      console.log('Discounted prices data:', cartItems);

      Object.entries(cartItems).forEach(([cartKey, { discounted_price_html, discounted_subtotal_html }]) => {
        const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
        if (!row) {
          console.warn(`Row not found for cart key: ${cartKey}`);
          return;
        }

        const priceCell = row.querySelector('.price-cell');
        const subtotalCell = row.querySelector('.line-subtotal');
        if (priceCell) priceCell.innerHTML = discounted_price_html;
        if (subtotalCell) subtotalCell.innerHTML = discounted_subtotal_html;
      });

      const originalTotalEl = document.querySelector('.original-total');
      const discountedTotalEl = document.querySelector('.discounted-total');
      if (originalTotalEl) originalTotalEl.innerHTML = originalTotalHtml;
      if (discountedTotalEl) discountedTotalEl.innerHTML = discountedTotalHtml;
    })
    .catch(console.error);
}

// Function to update cart item quantity via AJAX
function updateCartQuantity(cartKey, quantity) {
  return fetch(wc_cart_params.ajax_url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    //
    body: new URLSearchParams({
      action: 'update_cart_item',
      cart_item_key: cartKey,
      quantity: quantity
    })
  }).then(res => res.json());
}
