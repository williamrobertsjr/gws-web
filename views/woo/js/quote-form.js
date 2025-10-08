/**
 * Quote Form – cart → Gravity Forms bridge
 * - Waits for GF to be ready
 * - Mirrors "Test Tools" UI toggle to GF checkbox (Field 14, Choice 1 (checkbox))
 * - Shows/hides test-tools fields via Tailwind classes (toggle 'hidden')
 * - Populates GF fields from cart
 * - Submits GF and handles confirmation → clear cart + show message
 */

(function () {
  // ---------------------------
  // Selectors & constants
  // ---------------------------
  const SELECTORS = {
    sendBtn: '#send-quote',
    form: '#quote-form-wrapper form',
    uiToggle: '#test-tools-toggle',
    fields: '#test-tools-fields',
    gfCheckboxById: '#choice_41_14_1', // GF Form 41, Field 14, Choice 1 (checkbox)
    gfCheckbox: '#quote-form-wrapper input[type="checkbox"][name="input_14.1"]',
    iframe: 'iframe[name="gform_ajax_frame_41"]',
    cartRows: 'tbody tr',
    productName: 'td:nth-child(1) a',
    qtyInput: 'input[type="number"]',
    lineSubtotal: '.line-subtotal',
    lineOriginal: '.line-subtotal s',
    total: '.discounted-total',
    originalTotal: '.original-total',
    discountedTotal: '.discounted-total',
    role: '#userRole',
    coupon: '#coupon_code',
    email: '#userEmail',
    additionalComments: '#additional-comments',

    // Gravity Forms field names (by input_* names)
    gfProducts: 'textarea[name="input_1"]',
    gfOriginalTotal: 'input[name="input_3"]',
    gfDiscountedTotal: 'input[name="input_4"]',
    gfCoupon: 'input[name="input_5"]',
    gfRole: 'input[name="input_6"]',
    gfEmail: 'input[name="input_8"]',
    gfComments: 'textarea[name="input_9"]',
    // Test tools details
    gfCompany: 'input[name="input_11"]',
    gfContact: 'input[name="input_12"]',
    gfAddress: 'input[name="input_13"]'
  };

  let formReady = false;

  // ---------------------------
  // Utilities
  // ---------------------------
  function $(selector, root) {
    return (root || document).querySelector(selector);
  }

  function on(event, handler, root) {
    (root || document).addEventListener(event, handler);
  }

  function getText(selector) {
    return $(selector)?.textContent.trim() || '';
  }

  function getValue(selector) {
    return $(selector)?.value || '';
  }

  // ---------------------------
  // Test Tools: UI ↔ GF sync
  // ---------------------------
  function syncTestToolsUI() {
    const toggle = $(SELECTORS.uiToggle);
    const fields = $(SELECTORS.fields);
    if (fields) {
      // Only toggle visibility class; layout classes remain in markup
      fields.classList.toggle('hidden', !(toggle && toggle.checked));
    }
  }

  function findGfCheckbox() {
    // 1) Exact choice id (preferred)
    const byId = document.querySelector(SELECTORS.gfCheckboxById);
    if (byId) return byId;

    // 2) Container by field input id (GF renders checkbox list container with id input_41_14)
    const container = document.getElementById('input_41_14');
    if (container) {
      // Prefer checkbox with value "Yes" if present; else first checkbox in container
      const byValue = container.querySelector('input[type="checkbox"][value="Yes"]');
      if (byValue) return byValue;
      const anyInContainer = container.querySelector('input[type="checkbox"]');
      if (anyInContainer) return anyInContainer;
    }

    // 3) Fieldset by legend text, then label "Yes" -> input via 'for' attribute
    const fieldsets = document.querySelectorAll('fieldset.gfield');
    for (const fs of fieldsets) {
      const legend = fs.querySelector('legend');
      if (legend && /Is This a Test Tools Quote\?/i.test(legend.textContent || '')) {
        // Try label that says "Yes"
        const labels = fs.querySelectorAll('label[for]');
        for (const lbl of labels) {
          if (/^\s*Yes\s*$/i.test(lbl.textContent || '')) {
            const forId = lbl.getAttribute('for');
            const input = forId ? document.getElementById(forId) : null;
            if (input && input.type === 'checkbox') return input;
          }
        }
        // Fallback: first checkbox inside this fieldset
        const any = fs.querySelector('input[type="checkbox"]');
        if (any) return any;
      }
    }

    // 4) Generic fallback within the quote form wrapper
    const generic = document.querySelector('#quote-form-wrapper input[type="checkbox"][name="input_14.1"]')
                 || document.querySelector('#quote-form-wrapper input[type="checkbox"][name^="input_14"]')
                 || document.querySelector('input[type="checkbox"][name="input_14.1"]')
                 || document.querySelector('input[type="checkbox"][name^="input_14"]');
    if (generic) return generic;

    console.debug('⚠️ GF checkbox for Test Tools not found via any strategy.');
    return null;
  }

  // Prefer GF's official API when available (GF 2.5+)
  function setGfCheckboxValue(formId, fieldId, isChecked, choiceValue) {
    const values = isChecked ? [choiceValue] : [];
    try {
      if (window.gform && typeof gform.setFieldValue === 'function') {
        gform.setFieldValue(formId, fieldId, values);
        console.debug('🧰 gform.setFieldValue used for', { formId, fieldId, values });
        return true;
      }
      if (typeof window.gformSetFieldValue === 'function') {
        window.gformSetFieldValue(formId, fieldId, values);
        console.debug('🧰 gformSetFieldValue used for', { formId, fieldId, values });
        return true;
      }
    } catch (e) {
      console.warn('GF API setFieldValue failed, falling back to DOM', e);
    }
    return false;
  }

  function syncTestToolsCheckboxToGF() {
    const toggle = $(SELECTORS.uiToggle);
    const shouldCheck = !!(toggle && toggle.checked);

    // Try GF API first (Form 41, Field 14, choice "Yes")
    const apiSet = setGfCheckboxValue(41, 14, shouldCheck, 'Yes');
    if (apiSet) {
      console.debug('✅ Synced via GF API (Field 14) to', shouldCheck);
      return true;
    }

    // Fallback: DOM manipulation
    const cb = findGfCheckbox();
    if (!cb) {
      console.debug('🔎 GF checkbox (Field 14) not found yet; will retry.');
      return false;
    }
    if (cb.checked !== shouldCheck) {
      cb.checked = shouldCheck;
      cb.dispatchEvent(new Event('input', { bubbles: true }));
      cb.dispatchEvent(new Event('change', { bubbles: true }));
      if (typeof cb.click === 'function' && shouldCheck) {
        try { cb.click(); } catch (e) {}
      }
    } else {
      cb.dispatchEvent(new Event('change', { bubbles: true }));
    }
    console.debug('✅ Synced via DOM (Field 14) to', shouldCheck);
    return true;
  }

  function initTestToolsBindings() {
    const toggle = $(SELECTORS.uiToggle);
    syncTestToolsUI();
    syncTestToolsCheckboxToGF();
    if (toggle && !toggle.__gwsBound) {
      toggle.addEventListener('change', function () {
        console.log('🔀 Test Tools toggle changed:', toggle.checked);
        syncTestToolsUI();
        syncTestToolsCheckboxToGF();
      });
      toggle.__gwsBound = true;
    }
  }

  // Re-run sync when Gravity Forms finishes rendering (AJAX or otherwise)
  function bindGfRenderHook() {
    if (!window.jQuery) return;
    if (bindGfRenderHook.__bound) return;
    bindGfRenderHook.__bound = true;
    jQuery(document).on('gform_post_render', function (event, formId) {
      if (String(formId) === '41') {
        syncTestToolsUI();
        syncTestToolsCheckboxToGF();
      }
    });
  }

  // Observe wrapper for injected/replaced form markup
  function observeQuoteFormWrapper() {
    const wrapper = document.getElementById('quote-form-wrapper');
    if (!wrapper || wrapper.__gwsObserved) return;
    const observer = new MutationObserver(() => {
      // If the GF checkbox appears later, sync immediately
      const cb = findGfCheckbox();
      if (cb) {
        syncTestToolsUI();
        syncTestToolsCheckboxToGF();
      }
    });
    observer.observe(wrapper, { childList: true, subtree: true });
    wrapper.__gwsObserved = true;
  }

  // --- Direct binding: UI toggle -> GF "Yes" checkbox (Field 41/14/choice 1) ---
  function directBindToggleToGfYes() {
    const toggle = document.getElementById('test-tools-toggle');
    const gfYes = document.getElementById('choice_41_14_1') 
               || document.querySelector('#quote-form-wrapper input[type="checkbox"][name="input_14.1"]');
    if (!toggle || !gfYes) return;

    const sync = () => {
      const checked = !!toggle.checked;
      if (gfYes.checked !== checked) {
        gfYes.checked = checked;
        // fire events GF listens to
        gfYes.dispatchEvent(new Event('input', { bubbles: true }));
        gfYes.dispatchEvent(new Event('change', { bubbles: true }));
      } else {
        gfYes.dispatchEvent(new Event('change', { bubbles: true }));
      }
    };

    // initial pass
    sync();

    // bind once
    if (!toggle.__gwsYesBound) {
      toggle.addEventListener('change', sync);
      toggle.__gwsYesBound = true;
    }
  }

  // ---------------------------
  // Data collection & population
  // ---------------------------
  function collectCartProducts() {
    const rows = document.querySelectorAll(SELECTORS.cartRows);
    const items = [];
    rows.forEach(row => {
      const name = row.querySelector(SELECTORS.productName)?.textContent.trim() || '';
      const qty = row.querySelector(SELECTORS.qtyInput)?.value || '';
      const subtotal = row.querySelector(SELECTORS.lineSubtotal)?.textContent.trim() || '';
      items.push(`${name} × ${qty} — ${subtotal}`);
    });
    return items;
  }

  function populateGF(form) {
    // Ensure GF sees latest toggle state (API first, then DOM)
    const toggleNow = $(SELECTORS.uiToggle);
    const shouldCheckNow = !!(toggleNow && toggleNow.checked);
    if (!setGfCheckboxValue(41, 14, shouldCheckNow, 'Yes')) {
      syncTestToolsCheckboxToGF();
    }

    const products = collectCartProducts();

    const total = getText(SELECTORS.total);
    const originalTotal = getText(SELECTORS.originalTotal);
    const discountedTotal = getText(SELECTORS.discountedTotal);
    const role = getText(SELECTORS.role) || (window.cartQuoteData && window.cartQuoteData.role) || '';
    const coupon = getValue(SELECTORS.coupon);
    const email = getValue(SELECTORS.email) || (window.cartQuoteData && window.cartQuoteData.email) || '';
    const additionalComments = getValue(SELECTORS.additionalComments);

    // Test Tools inputs (from UI)
    const toggle = $(SELECTORS.uiToggle);
    const testToolsOn = !!(toggle && toggle.checked);
    const testToolsCompany = getValue('#test-tools-company');
    const testToolsContact = getValue('#test-tools-contact');
    const testToolsAddress = getValue('#test-tools-address');

    // Map base fields
    const productField = $(SELECTORS.gfProducts, form);
    const originalTotalField = $(SELECTORS.gfOriginalTotal, form);
    const discountedTotalField = $(SELECTORS.gfDiscountedTotal, form);
    const couponField = $(SELECTORS.gfCoupon, form);
    const roleField = $(SELECTORS.gfRole, form);
    const emailField = $(SELECTORS.gfEmail, form);
    const commentsField = $(SELECTORS.gfComments, form);

    if (productField) productField.value = products.join('\n');
    if (originalTotalField) originalTotalField.value = originalTotal || total;
    if (discountedTotalField) discountedTotalField.value = discountedTotal || total;
    if (couponField) couponField.value = coupon;
    if (roleField) roleField.value = role;
    if (emailField) emailField.value = email;
    if (commentsField) commentsField.value = additionalComments;

    // Test tools detail fields (only when toggle on; otherwise clear)
    const companyField = $(SELECTORS.gfCompany, form);
    const contactField = $(SELECTORS.gfContact, form);
    const addressField = $(SELECTORS.gfAddress, form);

    if (testToolsOn) {
      if (companyField) companyField.value = testToolsCompany;
      if (contactField) contactField.value = testToolsContact;
      if (addressField) addressField.value = testToolsAddress;
    } else {
      if (companyField) companyField.value = '';
      if (contactField) contactField.value = '';
      if (addressField) addressField.value = '';
    }
  }

  // ---------------------------
  // Submission flow
  // ---------------------------
  function waitForFormReady() {
    return new Promise(resolve => {
      const tryFind = () => {
        const form = $(SELECTORS.form);
        if (form) return resolve(form);
        setTimeout(tryFind, 200);
      };
      tryFind();
    });
  }

  function submitGF(form) {
    const btn = form.querySelector('input[type="submit"], button[type="submit"]');
    if (!btn) {
      console.warn('Gravity Forms submit button not found.');
      return;
    }
    btn.click();
  }

  function onConfirmIframeLoad() {
    const gformIframe = $(SELECTORS.iframe);
    if (!gformIframe || gformIframe.__gwsBound) return;

    gformIframe.addEventListener('load', function () {
      try {
        const confirmation = gformIframe.contentDocument.querySelector('#gform_confirmation_message_41');
        if (!confirmation) return;

        // Clear cart then show confirmation UI
        fetch('/wp-admin/admin-ajax.php?action=clear_cart', {
          method: 'POST',
          credentials: 'same-origin'
        })
          .then(res => res.json())
          .then(() => {
            const formSection = document.getElementById('cart-form');
            const quoteWrapper = document.getElementById('quote-form-wrapper');
            const summarySection = document.querySelector('#cart-page .w-4\\/12');

            if (formSection) formSection.style.display = 'none';
            if (quoteWrapper) quoteWrapper.style.display = 'none';
            if (summarySection) summarySection.style.display = 'none';

            const confirmationDiv = document.createElement('div');
            confirmationDiv.classList.add('text-white', 'p-4', 'rounded', 'mt-8', 'text-xl');
            confirmationDiv.innerHTML = `
              <h2 class="text-2xl font-bold mb-4">Quote Sent Successfully</h2>
              <p class="mb-4 text-lg">Your quote has been sent successfully. A confirmation email has been sent to you and a representative will reach out to you as soon as possible.</p>
              <div class="flex gap-4">
                <a href="/products" class="btn light-blue">Continue Browsing</a>
                <a href="mailto:sales@gwstoolgroup.com" class="btn black">Email Us</a>
              </div>
            `;
            const parentContainer = document.querySelector('#cart-page');
            parentContainer.appendChild(confirmationDiv);
            confirmationDiv.scrollIntoView({ behavior: 'smooth' });
          })
          .catch(err => console.error('❌ AJAX failed:', err));
      } catch (e) {
        console.error('⚠️ Could not access iframe contents:', e);
      }
    });

    gformIframe.__gwsBound = true;
  }

  // ---------------------------
  // Init
  // ---------------------------
  function init() {
    // Bind Test Tools UI + GF checkbox
    initTestToolsBindings();
    bindGfRenderHook();
    observeQuoteFormWrapper();

    directBindToggleToGfYes();
    if (window.jQuery) {
      jQuery(document).on('gform_post_render', function (e, formId) {
        if (String(formId) === '41') directBindToggleToGfYes();
      });
    }

    // Wire Send Quote button
    const sendBtn = $(SELECTORS.sendBtn);
    if (sendBtn && !sendBtn.__gwsBound) {
      sendBtn.addEventListener('click', async function () {
        const form = await waitForFormReady();
        if (!form) {
          alert('Form is still loading. Please wait a moment and try again.');
          return;
        }
        populateGF(form);
        submitGF(form);
      });
      sendBtn.__gwsBound = true;
    }

    // Confirmation listener
    onConfirmIframeLoad();
  }

  // Initial load
  on('DOMContentLoaded', init);
  // Re-init after WooCommerce fragment refreshes (cart pages often replace DOM)
  on('wc_fragments_refreshed', init);
  on('updated_wc_div', init);
  on('updated_cart_totals', init);
})();
