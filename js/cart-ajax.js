document.addEventListener("DOMContentLoaded", () => {
  // Resolve WooCommerce AJAX URL reliably
  const getAjaxUrl = () => (
    (window.wc_add_to_cart_params && wc_add_to_cart_params.ajax_url) ||
    (window.wc_cart_params && wc_cart_params.ajax_url) ||
    '/wp-admin/admin-ajax.php'
  );
  /**
   * Refresh Cart Table
   * --------------------------
   * Refetches the cart page, replaces <tbody> and totals,
   * then rebinds pricing logic if available.
   */
  let isCartBusy = false;
  async function refreshCartTable() {
    isCartBusy = true;
    try {
      const url = new URL(window.location.href);
      url.searchParams.delete('bulk_parts'); // prevent re-running bulk logic
      url.searchParams.set('_', Date.now().toString()); // cache-buster
      const res = await fetch(url.toString(), {
        method: "GET",
        credentials: "same-origin",
        cache: "no-store",
        headers: { "X-Requested-With": "XMLHttpRequest" }
      });
      const html = await res.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");

      // Replace only cart table and totals instead of full page
      const newTable = doc.querySelector("#cart-table");
      const currentTable = document.querySelector("#cart-table");
      if (newTable && currentTable) {
        currentTable.replaceWith(newTable);
      }

      const newTotals = doc.querySelector("#cart-totals");
      const currentTotals = document.querySelector("#cart-totals");
      if (newTotals && currentTotals) {
        currentTotals.replaceWith(newTotals);
      }

      if (!newTable || !newTotals) {
        // If something went wrong, hard reload so UI is not stale
        window.location.reload();
        return;
      }

      // Rebind pricing if available
      if (typeof window.rebindCartPricing === "function") {
        window.rebindCartPricing();
      }
      // Notify WooCommerce & any listeners that the cart DOM was updated
      if (window.jQuery) {
        jQuery(document.body).trigger('updated_wc_div');
        // jQuery(document.body).trigger('wc_fragment_refresh');
        // jQuery(document.body).trigger('wc_fragments_refreshed');
      }
      isCartBusy = false;
    } catch (err) {
      console.error("Error refreshing cart:", err);
      // As a safety, do a full reload
      window.location.reload();
    }
  }

  // Expose globally for multipart-add.js to call
  window.refreshCartTable = refreshCartTable;

  /**
   * Remove Item
   * --------------------------
   * Listens for clicks on .remove-item buttons,
   * sends AJAX request to remove cart item,
   * then refreshes the cart table.
   */
  document.body.addEventListener("click", async (e) => {
    if (isCartBusy) return;
    const btn = e.target.closest(".remove-item");
    if (!btn) return;
    e.preventDefault();

    const cartKey = btn.dataset.cartKey;
    if (!cartKey) return;

    // Optional UI lock
    const prevHtml = btn.innerHTML;
    btn.disabled = true;
    btn.setAttribute("aria-busy", "true");

    try {
      const res = await fetch(getAjaxUrl(), {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          action: "remove_cart_item",
          cart_item_key: cartKey,
        }),
      });

      const data = await res.json();
      if (data && data.success) {
        await refreshCartTable();
      } else {
        console.error("Remove failed:", data);
        // Fallback: reload page so user isn't left with stale UI
        window.location.reload();
      }
    } catch (err) {
      console.error("Remove error:", err);
      window.location.reload();
    } finally {
      btn.disabled = false;
      btn.removeAttribute("aria-busy");
      btn.innerHTML = prevHtml;
    }
  });
});