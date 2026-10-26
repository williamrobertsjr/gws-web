// Fills in everything that used to be baked into server HTML per-visitor
// (tier/company price, stock, nav state, tier-selector visibility, discount
// label, cart count) so the page itself renders identically for every
// visitor and can be served from Breeze's page cache. Runs for every
// visitor -- see inc/enqueue-scripts.php for why this can't be gated behind
// is_user_logged_in() server-side once caching is on.
document.addEventListener('DOMContentLoaded', function () {
  // Cart count: fetched for everyone, since a guest can have items in a WC
  // session without ever logging in.
  fetch('/wp-admin/admin-ajax.php?action=get_cart_count')
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success && data.data && data.data.count !== undefined) {
        var el = document.getElementById('cart-count');
        if (el) el.textContent = data.data.count;
      }
    })
    .catch(function () {});

  // Everything below only applies to logged-in visitors. WordPress's own
  // session cookie (wordpress_logged_in_*) is httponly and can't be read
  // here, so inc/login.php sets this separate, JS-readable marker at
  // login/logout instead -- a guest's browser runs this same script but
  // stops here, firing no further requests.
  var isLoggedIn = document.cookie.split('; ').some(function (c) {
    return c.indexOf('gws_logged_in=1') === 0;
  });
  if (!isLoggedIn) return;

  // Skip the automatic price refresh if a manually-selected tier (sales/admin
  // tier-selector) is about to run its own refresh on this same load.
  if (!localStorage.getItem('selectedTier') && typeof window.gwsRefreshPrices === 'function') {
    window.gwsRefreshPrices();
  }

  fetch('/wp-admin/admin-ajax.php?action=gws_visitor_state')
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success && data.data) gwsApplyVisitorState(data.data);
    })
    .catch(function (err) { console.error('Visitor state error:', err); });

  // Simple "In stock" badges (archive/series tiles) -- batched into one
  // request for the whole page instead of one fetch per tile, reusing the
  // same .gws-live-stock/data-gws-sku convention product-helpers.js uses for
  // the product table.
  var stockEls = {};
  document.querySelectorAll('.gws-live-stock[data-gws-sku]').forEach(function (el) {
    var sku = el.dataset.gwsSku;
    if (sku) stockEls[sku] = el;
  });
  var stockSkus = Object.keys(stockEls);
  if (stockSkus.length) {
    fetch('/wp-json/gws/v1/stock-batch?skus=' + encodeURIComponent(stockSkus.join(',')))
      .then(function (res) { return res.json(); })
      .then(function (results) {
        Object.keys(results).forEach(function (sku) {
          if (stockEls[sku]) stockEls[sku].textContent = results[sku].found ? results[sku].available : '—';
        });
      })
      .catch(function () {});
  }

  // Full CA/IL stock breakdown (single product page).
  document.querySelectorAll('[data-stock-sku]').forEach(function (el) {
    var sku = el.dataset.stockSku;
    fetch('/wp-json/gws/v1/stock/' + encodeURIComponent(sku))
      .then(function (res) { return res.json(); })
      .then(function (stock) {
        if (stock.found) el.innerHTML = gwsRenderStock(stock);
      })
      .catch(function () {});
  });
});

function gwsApplyVisitorState(state) {
  gwsSetAuthVisible('[data-auth-nav="in"]', state.logged_in);
  gwsSetAuthVisible('[data-auth-nav="out"]', !state.logged_in);

  var tierSelectorWrap = document.getElementById('tier-selector-wrap');
  if (tierSelectorWrap) tierSelectorWrap.classList.toggle('hidden', !state.show_tier_selector);

  if (state.role_display) {
    document.querySelectorAll('#discount-tier-label').forEach(function (el) {
      el.textContent = state.role_display;
    });
  }
}

// "contents" (rather than just un-hiding) keeps a wrapper's children acting
// as direct flex children of their parent, matching how these elements laid
// out before they were wrapped for client-side toggling (see base.twig).
function gwsSetAuthVisible(selector, visible) {
  document.querySelectorAll(selector).forEach(function (el) {
    if (el.dataset.authDisplay === 'contents') {
      el.classList.toggle('hidden', !visible);
      el.classList.toggle('contents', visible);
    } else {
      el.classList.toggle('hidden', !visible);
    }
  });
}

function gwsRenderStock(stock) {
  var total = Number(stock.total_stock || 0).toLocaleString('en-US');

  if (stock.durrie_qty_on_hand !== null && stock.durrie_qty_on_hand !== undefined) {
    var ca = Number(stock.qty_on_hand || 0).toLocaleString('en-US');
    var il = Number(stock.durrie_qty_on_hand).toLocaleString('en-US');
    return (
      '<div class="p-1">' +
        '<div class="bg-dark-blue px-2 text-white">' +
          '<p class="mb-0 text-lg"><span class="font-bold product-stock mb-2">Stock</span>: ' + total + '</p>' +
        '</div>' +
        '<div class="flex gap-x-2 px-2">' +
          '<p class="text-sm"><span class="font-medium">CA</span>: ' + ca + '</p>' +
          '<p class="text-sm"><span class="font-medium">IL</span>: ' + il + '</p>' +
        '</div>' +
      '</div>'
    );
  }

  return (
    '<div class="bg-dark-blue px-2 text-white">' +
      '<p class="p-1 text-lg"><span class="font-bold product-stock">Stock</span>: ' + total + '</p>' +
    '</div>'
  );
}
