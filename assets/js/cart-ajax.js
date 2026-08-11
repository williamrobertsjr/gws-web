document.addEventListener("DOMContentLoaded", () => {
  // Resolve WooCommerce AJAX URL reliably
  const getAjaxUrl = () => (
    (window.wc_add_to_cart_params && wc_add_to_cart_params.ajax_url) ||
    (window.wc_cart_params && wc_cart_params.ajax_url) ||
    '/wp-admin/admin-ajax.php'
  );

  /**
   * Clear Test Tools Fields
   * --------------------------
   * Resets test tools form fields and totals display
   */
  function clearTestToolsFields() {
    // Clear test tools inputs
    const testToolsContact = document.querySelector('#test-tools-contact');
    const testToolsCompany = document.querySelector('#test-tools-company');
    const testToolsAddress = document.querySelector('#test-tools-address');
    const additionalComments = document.querySelector('#additional-comments');
    
    if (testToolsContact) testToolsContact.value = '';
    if (testToolsCompany) testToolsCompany.value = '';
    if (testToolsAddress) testToolsAddress.value = '';
    if (additionalComments) additionalComments.value = '';

    // Uncheck the test tools toggle
    const testToolsToggle = document.querySelector('#test-tools-toggle');
    if (testToolsToggle) {
      testToolsToggle.checked = false;
      // Trigger change event to hide the fields
      testToolsToggle.dispatchEvent(new Event('change'));
    }

    // Reset totals display
    const originalTotal = document.querySelector('.original-total');
    const discountedTotal = document.querySelector('.discounted-total');
    
    if (originalTotal) originalTotal.textContent = '$0.00';
    if (discountedTotal) discountedTotal.textContent = '$0.00';
  }

  // Expose globally so quote-form.js can call it
  window.clearTestToolsFields = clearTestToolsFields;

  /**
   * Refresh Cart Table
   * --------------------------
   * Refetches the cart page, replaces <tbody> and totals,
   * then rebinds pricing logic if available.
   */
  let isCartBusy = false;
  async function refreshCartTable() {
    if (isCartBusy) return;
    isCartBusy = true;

    try {
      const url = new URL(window.location.href);
      url.searchParams.delete("bulk_parts");
      url.searchParams.set("_", Date.now().toString()); // cache-buster

      const res = await fetch(url.toString(), {
        method: "GET",
        credentials: "same-origin",
        cache: "no-store",
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });

      const html = await res.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");

      // --- Replace only the <tbody> inside #cart-table ---
      const newTbody = doc.querySelector("#cart-table tbody");
      const currentTbody = document.querySelector("#cart-table tbody");

      if (newTbody && currentTbody) {
        currentTbody.replaceWith(newTbody);
      } else {
        console.warn("Cart refresh: no <tbody> found in response; skipping update.");
      }

      // Replace totals
      const newOriginal = doc.querySelector('.original-total');
      const newDiscounted = doc.querySelector('.discounted-total');
      const currentOriginal = document.querySelector('.original-total');
      const currentDiscounted = document.querySelector('.discounted-total');

      if (newOriginal && currentOriginal) currentOriginal.innerHTML = newOriginal.innerHTML;
      if (newDiscounted && currentDiscounted) currentDiscounted.innerHTML = newDiscounted.innerHTML;

      // // --- Replace totals section ---
      // const newTotals = doc.querySelector("#cart-totals");
      // console.log("New Totals:", newTotals);
      // const currentTotals = document.querySelector("#cart-totals");
      // console.log("Current Totals:", currentTotals);
      // if (newTotals && currentTotals) {
      //   console.log("Replacing cart totals...");
      //   currentTotals.replaceWith(newTotals);
      // }

     
      if (typeof window.bindCartQtyListeners === "function") {
        window.bindCartQtyListeners();
      }

      // --- Update pricing/totals based on current tier ---
      if (typeof window.updatePricesByTier === 'function') {
        const savedTier = localStorage.getItem('selectedTier');
        if (savedTier) {
          window.updatePricesByTier(savedTier);
        }
        // For regular users, server-rendered totals are already correct — do nothing
      }

      // --- Notify WooCommerce + custom events ---
      if (window.jQuery) {
        jQuery(document.body).trigger("updated_wc_div");
      }

    } catch (err) {
      console.error("Error refreshing cart:", err);
    } finally {
      isCartBusy = false;
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

  /**
   * Show Feedback Message
   * --------------------------
   * Displays a message in #bulk-add-feedback and auto-clears after duration
   */
  let feedbackTimer = null;

  function showFeedback(message, duration = 5000) {
    const feedback = document.getElementById('bulk-add-feedback');
    if (!feedback) return;
    
    // Clear any existing timer
    if (feedbackTimer) {
      clearTimeout(feedbackTimer);
    }
    
    // Set the message
    feedback.innerHTML = message;
    
    // Set new timer to clear
    feedbackTimer = setTimeout(() => {
      feedback.innerHTML = '';
    }, duration);
  }

  // Expose globally
  window.showFeedback = showFeedback;
});