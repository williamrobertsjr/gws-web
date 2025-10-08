// cart-pricing.js
// This script updates cart item prices based on the selected tier.
// It listens for changes in the tier selection and updates the displayed prices accordingly.
// It also handles quantity changes for cart items and updates the total prices.
// The script uses AJAX to fetch the updated prices and updates the DOM elements accordingly.
// It also restores the pricing on initial load if a tier is already selected.
document.addEventListener('DOMContentLoaded', function () {
  const updatePricesByTier = (tier) => {
    // Update cart pricing if present
    fetch(`/wp-admin/admin-ajax.php?action=get_discounted_prices_by_tier&tier=${tier}`, {
      method: 'GET',
      credentials: 'same-origin'
    })
      .then(res => res.json())
      .then(response => {
        const cartItems = response.data.cart_items || {};
        const originalTotalHtml = response.data.original_total_html;
        const discountedTotalHtml = response.data.discounted_total_html;

        Object.entries(cartItems).forEach(([cartKey, { discounted_price_html, discounted_subtotal_html }]) => {
          const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
          if (!row) return;

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

    // Update archive/product pricing if present
    const productPriceCells = document.querySelectorAll('.price-cell[data-product-id]');
console.log('productPriceCells found:', productPriceCells.length); // ✅ STEP 1

if (productPriceCells.length > 0) {
  const productIds = Array.from(productPriceCells).map(el => el.dataset.productId);
  console.log('Product IDs:', productIds); // ✅ STEP 2

  fetch(`/wp-admin/admin-ajax.php?action=get_discounted_product_prices_by_tier&tier=${tier}&product_ids=${productIds.join(',')}`, {
    method: 'GET',
    credentials: 'same-origin'
  })
    .then(res => res.json())
    .then(response => {
      console.log('Response from server:', response); // ✅ STEP 3

      if (!response.success || !response.data || typeof response.data !== 'object') return;

      Object.entries(response.data).forEach(([productId, htmlObj]) => {
        const priceHtml = htmlObj.discounted_price_html;
        console.log(`Updating price for product ${productId}:`, priceHtml); // ✅ STEP 4

        document.querySelectorAll(`.price-cell[data-product-id="${productId}"]`).forEach(el => {
          el.innerHTML = priceHtml;
        });
      });
    })
    .catch(console.error);
    }
  };

  // Listen for tier changes
  document.addEventListener('tierChanged', (e) => {
    updatePricesByTier(e.detail.tier);
  });

  // Restore pricing on initial load if tier is selected
  const savedTier = localStorage.getItem('selectedTier');
  if (savedTier) {
    updatePricesByTier(savedTier);
  }

  // Quantity input events
  document.querySelectorAll('.cart-qty-input').forEach(input => {
    let timer;

    const handler = () => {
        console.log('Quantity change detected');
      const cartKey = input.dataset.cartKey;
      const quantity = input.value;

      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'update_cart_item',
          cart_item_key: cartKey,
          quantity: quantity
        })
      })
        .then(res => {
          if (!res.ok) throw new Error('Bad response');
          return res.json();
        })
        .then(data => {
          if (data && data.success) {
            const currentTier = localStorage.getItem('selectedTier');
            if (currentTier) {
              updatePricesByTier(currentTier);
            }
          } else {
            console.warn('Qty update failed, refreshing cart table');
            if (typeof refreshCartTable === 'function') {
              refreshCartTable();
            }
          }
        })
        .catch(error => {
          console.error('Cart update failed:', error);
          if (typeof refreshCartTable === 'function') {
            refreshCartTable();
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
});