/**
 * Quote Form – cart → Quote API bridge
 * - Mirrors "Test Tools" UI toggle
 * - Shows/hides test-tools fields via Tailwind classes (toggle 'hidden')
 * - Gathers quote data from cart
 * - Submits quote via API and handles confirmation → clear cart + show message
 */

(function () {
  // ---------------------------
  // Selectors & constants
  // ---------------------------
  const SELECTORS = {
      // existing selectors...
      sendBtn: '#send-quote',
      uiToggle: '#test-tools-toggle',
      fields: '#test-tools-fields',
      cartRows: 'tbody tr',
      total: '.discounted-total',
      originalTotal: '.original-total',
      discountedTotal: '.discounted-total',
      role: '#userRole',
      email: '#user-email',
      userName: '#user-name',
      userCompany: '#user-company',
      customerCompany: '#customer-company',
      additionalComments: '#additional-comments',
      testToolsContact: '#test-tools-contact',
      testToolsCompany: '#test-tools-company',
      testToolsAddress: '#test-tools-address',
      // modal
      salesModal: '#sales-email-modal',
      salesModalSend: '#sales-modal-send',
      salesModalCancel: '#sales-modal-cancel',
      customerName: '#customer-name',
      customerEmail: '#customer-email',
      customerMessage: '#customer-message'
  };

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
  // Test Tools: UI sync
  // ---------------------------
  function syncTestToolsUI() {
    const toggle = $(SELECTORS.uiToggle);
    const fields = $(SELECTORS.fields);
    if (fields) fields.classList.toggle('hidden', !(toggle && toggle.checked));
  }
  function initTestToolsBindings() {
    const toggle = $(SELECTORS.uiToggle);
    const sendBtn = $(SELECTORS.sendBtn);
    syncTestToolsUI();
    if (toggle && !toggle.__gwsBound) {
     
      // Update selector for Gravity Forms checkbox
      const gformTestToolsInput = document.querySelector('input[name="input_14.1"][type="checkbox"]');
      toggle.addEventListener('change', function () {
        syncTestToolsUI();
        sendBtn.innerText = toggle.checked ? 'Email Test Tools Quote' : 'Email Quote';
        if (gformTestToolsInput) {
          gformTestToolsInput.checked = toggle.checked;
          gformTestToolsInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
      toggle.__gwsBound = true;
      if (gformTestToolsInput) {
        gformTestToolsInput.checked = toggle.checked;
      }
    }
  }

  // ---------------------------
  // Quote Table + Quote ID
  // ---------------------------
  function generateQuoteId() {
    const datePart = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const rand = Math.random().toString(36).substring(2, 6).toUpperCase();
    return `GWS-${datePart}-${rand}`;
  }

  function buildQuoteTableHTML() {
    const rows = document.querySelectorAll('#cart-table tbody tr');
    if (!rows.length) return '<p>No products found.</p>';

    let html = `
      <table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;width:100%;font-family:Helvetica,Arial,sans-serif;font-size:13px;border:1px solid #ddd;">
        <thead>
          <tr style="background:#f7f7f7;font-weight:bold;">
            <th>Part #</th>
            <th>Description</th>
            <th>List</th>
            <th>Net</th>
            <th>Stock</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
    `;

    rows.forEach(row => {
      const part = row.querySelector('td:nth-child(1) a, td:nth-child(1)')?.innerText.trim() || '';
      const desc = row.querySelector('td:nth-child(2)')?.innerText.trim() || '';
      const list = row.querySelector('.list-price-cell, td:nth-child(3)')?.innerText.trim() || '';
      const net = row.querySelector('.price-cell, td:nth-child(4)')?.innerText.trim() || '';
      const stock = row.querySelector('.stock-cell, td:nth-child(5)')?.innerText.trim() || '';
      const qty = row.querySelector('input[type="number"], td:nth-child(6)')?.value || '';
      const subtotal = row.querySelector('.line-subtotal, td:nth-child(7)')?.innerText.trim() || '';

      html += `
        <tr>
          <td>${part}</td>
          <td>${desc}</td>
          <td>${list}</td>
          <td>${net}</td>
          <td>${stock}</td>
          <td>${qty}</td>
          <td>${subtotal}</td>
        </tr>
      `;
    });

    const total = document.querySelector('.discounted-total')?.innerText.trim() || '';
    html += `
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" style="text-align:right;font-weight:bold;">Total:</td>
            <td>${total}</td>
          </tr>
        </tfoot>
      </table>
    `;
    return html;
  }

  // ---------------------------
  // Quote submission via API
  // ---------------------------
  async function sendQuoteViaAPI(overrides = {}) {
    const overlay = document.getElementById('email-sending-overlay');
    if (overlay) overlay.classList.remove('hidden');

    const quoteId = generateQuoteId();
    const tableHTML = buildQuoteTableHTML();

    const originalTotal = getText(SELECTORS.originalTotal);
    const discountedTotal = getText(SELECTORS.discountedTotal);
    const tierSelector = document.querySelector('#tier-selector');
    const role = window.cartQuoteData.isSales && tierSelector && tierSelector.value
        ? window.cartQuoteData.tierLabels[tierSelector.value] || getText(SELECTORS.role)
        : getText(SELECTORS.role);
    const additionalComments = getValue(SELECTORS.additionalComments);
    const toggle = $(SELECTORS.uiToggle);
    const testToolsOn = !!(toggle && toggle.checked);

    // Use overrides for sales, otherwise use hidden input values
    const name = overrides.name || getValue(SELECTORS.userName) || '';
    const email = overrides.email || getValue(SELECTORS.email) || '';
    const company = overrides.company || getValue(SELECTORS.userCompany) || '';
    const customerMessage = overrides.message || '';

    // Collect items
    const items = [];
    const rows = document.querySelectorAll('#cart-table tbody tr');
    rows.forEach(row => {
        const part = row.querySelector('td:nth-child(1) a, td:nth-child(1)')?.innerText.trim() || '';
        const desc = row.querySelector('td:nth-child(2)')?.innerText.trim() || '';
        const list = row.querySelector('.list-price-cell, td:nth-child(3)')?.innerText.trim() || '';
        const net = row.querySelector('.price-cell, td:nth-child(4)')?.innerText.trim() || '';
        const stock = row.querySelector('.stock-cell, td:nth-child(5)')?.innerText.trim() || '';
        const qty = row.querySelector('.cart-qty-input')?.value || '';
        const subtotal = row.querySelector('.line-subtotal, td:nth-child(7)')?.innerText.trim() || '';
        items.push({ part, desc, list, net, stock, qty, subtotal });
    });

    const payload = {
        quote_id: quoteId,
        name: name,
        email: email,
        company: company,
        sales_email: overrides.salesEmail || window.cartQuoteData.email || '',
        sales_name: overrides.salesName || getValue(SELECTORS.userName) || '',
        comments: additionalComments,
        customer_message: customerMessage,
        test_tools: testToolsOn,
        role_label: role,
        testToolsContact: testToolsOn ? getValue(SELECTORS.testToolsContact) : '',
        testToolsCompany: testToolsOn ? getValue(SELECTORS.testToolsCompany) : '',
        testToolsAddress: testToolsOn ? getValue(SELECTORS.testToolsAddress) : '',
        items: items,
        totals: {
            original: originalTotal,
            discounted: discountedTotal
        },
        table_html: tableHTML
    };

    try {
        const response = await fetch('/wp-json/gws/v1/send-quote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });

        if (!response.ok) throw new Error(`Server responded with status ${response.status}`);

        await fetch('/wp-admin/admin-ajax.php?action=clear_cart', {
            method: 'POST',
            credentials: 'same-origin'
        });

        if (typeof refreshCartTable === 'function') await refreshCartTable();
        if (typeof clearTestToolsFields === 'function') clearTestToolsFields();

        showFeedback('<span style="font-size:20px; color:#f7f7f7;">Your email has been sent.</span>');
    } catch (err) {
        alert('An error occurred while sending your quote. Please try again.');
        console.error('Error sending quote:', err);
    } finally {
        if (overlay) overlay.classList.add('hidden');
    }
  }

  // ---------------------------
  // Init
  // ---------------------------
  function init() {
    initTestToolsBindings();

    const sendBtn = $(SELECTORS.sendBtn);
    if (sendBtn && !sendBtn.__gwsBound) {
      sendBtn.addEventListener('click', async function () {
          const testToolsOn = !!($(SELECTORS.uiToggle) && $(SELECTORS.uiToggle).checked);
          
          if (window.cartQuoteData.isSales && !testToolsOn) {
              document.querySelector(SELECTORS.salesModal).classList.remove('hidden');
          } else {
              await sendQuoteViaAPI();
          }
      });
      sendBtn.__gwsBound = true;
    }

    // Modal send button
    const modalSendBtn = $(SELECTORS.salesModalSend);
    if (modalSendBtn && !modalSendBtn.__gwsBound) {
        modalSendBtn.addEventListener('click', async function () {
            const customerName = getValue(SELECTORS.customerName);
            const customerEmail = getValue(SELECTORS.customerEmail);
            const customerMessage = getValue(SELECTORS.customerMessage);

            if (!customerEmail) {
                alert('Please enter a customer email address.');
                return;
            }

            document.querySelector(SELECTORS.salesModal).classList.add('hidden');
            await sendQuoteViaAPI({
                name: customerName,
                email: customerEmail,
                company: getValue(SELECTORS.customerCompany),
                message: customerMessage,
                salesEmail: window.cartQuoteData.email,
                salesName: getValue(SELECTORS.userName)
            });
        });
        modalSendBtn.__gwsBound = true;
    }

    // Modal cancel button
    const modalCancelBtn = $(SELECTORS.salesModalCancel);
    if (modalCancelBtn && !modalCancelBtn.__gwsBound) {
        modalCancelBtn.addEventListener('click', function () {
            document.querySelector(SELECTORS.salesModal).classList.add('hidden');
        });
        modalCancelBtn.__gwsBound = true;
    }
  }

  on('DOMContentLoaded', init);
  on('wc_fragments_refreshed', init);
  on('updated_wc_div', init);
  on('updated_cart_totals', init);
})();