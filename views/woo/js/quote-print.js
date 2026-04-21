document.addEventListener('DOMContentLoaded', function() {
  const printBtn = document.getElementById('print-quote');
  if (!printBtn) return;

  printBtn.addEventListener('click', async function() {
    // Check if test tools mode is enabled
    const testToolsToggle = document.getElementById('test-tools-toggle');
    const isTestTools = testToolsToggle && testToolsToggle.checked;
    
    if (isTestTools) {
      alert('Test tools requests cannot be printed. Please use the email option instead.');
      return;
    }

    // Gather quote data
    const name = document.getElementById('user-name')?.value || '';
    const email = document.getElementById('user-email')?.value || '';
    const company = document.getElementById('user-company')?.value || '';
    const comments = document.getElementById('additional-comments')?.value || '';
    const tierSelector = document.querySelector('#tier-selector');
    const roleLabel = window.cartQuoteData.isSales && tierSelector && tierSelector.value
        ? window.cartQuoteData.tierLabels[tierSelector.value] || document.getElementById('userRole')?.textContent.trim() || ''
        : document.getElementById('userRole')?.textContent.trim() || '';

    // Collect items from table
    const items = [];
    // const tableRows = document.querySelectorAll('#PDFQuote table tbody tr')
    const tableRows = document.querySelectorAll('#cart-table tbody tr')
    console.log('tableRows found:', tableRows.length);;
    tableRows.forEach(row => {
        const part = row.querySelector('td:nth-child(1) a')?.textContent.trim() || '';
        const desc = row.querySelector('td:nth-child(2)')?.textContent.trim() || '';
        const list = row.querySelector('.list-price-cell')?.textContent.trim() || '';
        const net = row.querySelector('.price-cell')?.textContent.trim() || '';
        const stock = row.querySelector('td:nth-child(5)')?.textContent.trim() || '';
        const qty = row.querySelector('.cart-qty-input')?.value || '';
        const subtotal = row.querySelector('.line-subtotal')?.textContent.trim() || '';
        console.log('part:', part, '| desc:', desc, '| list:', list, '| net:', net);

        if (part) {
            items.push({ part, desc, list, net, stock, qty, subtotal });
        }
    });

    // Collect totals
    const originalTotal = document.querySelector('.original-total')?.textContent.trim() || '';
    const discountedTotal = document.querySelector('.discounted-total')?.textContent.trim() || '';
    const totals = {
      original: originalTotal,
      discounted: discountedTotal
    };

    // Show loading state
    const originalText = printBtn.textContent;
    printBtn.disabled = true;
    printBtn.textContent = 'Generating PDF...';

    try {
      const response = await fetch('/wp-json/gws/v1/print-quote', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          name,
          email,
          company,
          comments,
          role_label: roleLabel,
          test_tools: false,
          items,
          totals
        })
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Failed to generate PDF');
      }

      // Get PDF blob and open in new window
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      window.open(url, '_blank');
      
      // Clean up
      setTimeout(() => window.URL.revokeObjectURL(url), 100);
      
    } catch (error) {
      console.error('Print error:', error);
      alert('Failed to generate quote PDF: ' + error.message);
    } finally {
      printBtn.disabled = false;
      printBtn.textContent = originalText;
    }
  });
});