// cart-pricing.js
// This script updates cart item prices based on the selected tier.
// It listens for changes in the tier selection and updates the displayed prices accordingly.
// It also handles quantity changes for cart items and updates the total prices.
// The script uses AJAX to fetch the updated prices and updates the DOM elements accordingly.
// It also restores the pricing on initial load if a tier is already selected.
function debounce(fn, delay = 300) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

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

        // console.log('Prices updated for cart items:', response.data);
      })
      .catch(console.error);

    // Update archive/product pricing if present (only on non-cart pages)
    const productPriceCells = document.querySelectorAll('.price-cell[data-product-id]');
    const isCartPage = document.body.classList.contains('woocommerce-cart');

    if (!isCartPage && productPriceCells.length > 0) {
      const productIds = Array.from(productPriceCells)
        .map(el => el.dataset.productId)
        .filter(Boolean);

      if (productIds.length === 0) return;

      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'get_discounted_product_prices_by_tier',
          tier: tier,
          product_ids: productIds.join(',')
        })
      })
        .then(res => res.json())
        .then(response => {
          if (!response.success || !response.data || typeof response.data !== 'object') return;

          Object.entries(response.data).forEach(([productId, htmlObj]) => {
            const priceHtml = htmlObj.discounted_price_html;

            document.querySelectorAll(`.price-cell[data-product-id="${productId}"]`).forEach(el => {
              el.innerHTML = priceHtml;
            });
          });
        })
        .catch(console.error);
    }
    };

  const debouncedUpdatePricesByTier = debounce(updatePricesByTier, 500);

  // Listen for tier changes
  document.addEventListener('tierChanged', (e) => {
    // console.log('tierChanged event received with tier:', e.detail.tier);
    debouncedUpdatePricesByTier(e.detail.tier);
  });

  // Restore pricing on initial load if tier is selected
  setTimeout(() => {
    const savedTier = localStorage.getItem('selectedTier');
    const isCartPage = document.body.classList.contains('woocommerce-cart');
    const hasPriceCells = document.querySelector('.price-cell[data-product-id]') || document.querySelector('tr[data-cart-key]');

    if (savedTier) {
      debouncedUpdatePricesByTier(savedTier);
    }
  }, 500);

  // Ensure tier pricing re-applies on full reloads (e.g. hard refresh by sales users)
  window.addEventListener('load', () => {
    const savedTier = localStorage.getItem('selectedTier');
    const isCartPage = document.body.classList.contains('woocommerce-cart');
    const hasPriceCells = document.querySelector('.price-cell[data-product-id]') || document.querySelector('tr[data-cart-key]');
    if (savedTier) {
      // console.log('Reapplying saved tier after full load:', savedTier);
      updatePricesByTier(savedTier);
    }
  });

  bindCartQtyListeners();
});

function bindCartQtyListeners() {
  document.querySelectorAll('.cart-qty-input').forEach(input => {
    let timer;

    const handler = () => {
      // console.log('Quantity change detected');
      const cartKey = input.dataset.cartKey;
      const quantity = input.value;
      // console.log('Firing fetch with:', { cartKey, quantity });

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
              document.dispatchEvent(new CustomEvent('tierChanged', {
                detail: { tier: currentTier }
              }));
            } else if (data.data && data.data.cart_items) {
              const cartItems = data.data.cart_items || {};
              const originalTotalHtml = data.data.original_total_html;
              const discountedTotalHtml = data.data.discounted_total_html;

              Object.entries(cartItems).forEach(([cartKey, itemData]) => {
                if (typeof itemData !== 'object') return;
                const row = document.querySelector(`tr[data-cart-key="${cartKey}"]`);
                if (!row) return;

                const subtotalCell = row.querySelector('.line-subtotal');
                if (subtotalCell && itemData.subtotal) subtotalCell.innerHTML = itemData.subtotal;
              });

              const originalTotalEl = document.querySelector('.original-total');
              const discountedTotalEl = document.querySelector('.discounted-total');
              if (originalTotalEl && originalTotalHtml) originalTotalEl.innerHTML = originalTotalHtml;
              if (discountedTotalEl && discountedTotalHtml) discountedTotalEl.innerHTML = discountedTotalHtml;
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
            // console.log('Calling refreshCartTable() from catch block');
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
}
window.bindCartQtyListeners = bindCartQtyListeners;