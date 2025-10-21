document.addEventListener('DOMContentLoaded', function() {
  const printBtn = document.getElementById('print-quote');
  if (!printBtn) return;

  printBtn.addEventListener('click', function() {
    const pdfQuote = document.getElementById('PDFQuote');

    // Declare variables for cleanup function access
    let qtyInputs = [];
    let additionalComments = null;

    if (pdfQuote) {
      qtyInputs = pdfQuote.querySelectorAll('input.qty, input[type="number"]');
      qtyInputs.forEach(function(input) {
        // Prevent duplicate preview spans
        if (!input.nextSibling || input.nextSibling.tagName !== 'SPAN') {
          if (input.value.trim() !== '') {
            const preview = document.createElement('span');
            preview.textContent = input.value;
            input.style.display = 'none';
            input.parentNode.insertBefore(preview, input.nextSibling);
          }
        }
      });

      additionalComments = document.getElementById('additional-comments');
      if (additionalComments && additionalComments.value.trim() !== '') {
        // Prevent duplicate comment previews
        if (!additionalComments.nextSibling || additionalComments.nextSibling.tagName !== 'DIV') {
          const commentsPreview = document.createElement('div');
          const commentsHeader = document.createElement('h3');
          commentsHeader.textContent = 'Additional Comments:';
          const commentsBody = document.createElement('p');
          commentsBody.textContent = additionalComments.value;
          commentsPreview.appendChild(commentsHeader);
          commentsPreview.appendChild(commentsBody);
          additionalComments.style.display = 'none';
          additionalComments.parentNode.insertBefore(commentsPreview, additionalComments.nextSibling);
        }
      }
    }

    function cleanupPreviews() {
      qtyInputs.forEach(function(input) {
        input.style.display = '';
        if (input.nextSibling && input.nextSibling.tagName === 'SPAN') {
          input.nextSibling.remove();
        }
      });
      if (additionalComments) {
        additionalComments.style.display = '';
        if (additionalComments.nextSibling && additionalComments.nextSibling.tagName === 'DIV') {
          additionalComments.nextSibling.remove();
        }
      }
    }

    printJS({
      printable: 'PDFQuote',
      type: 'html',
      targetStyles: ['*'],
      ignoreElements: ['toolImage', 'closeBtnHeader', 'closeBtnCol', 'stockUpdated', 'userRoleLevel', 'test-tools-toggle', 'test-tools-fields', 'additional-comments'],
      scanStyles: false,
      style: `
        @page {
          size: letter;
          margin: 0.75in;
        }
        body {
          font-family: "Helvetica Neue", Arial, sans-serif;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }
        #pdf-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-bottom: 1px solid #ccc;
          margin-bottom: 20px;
          padding-bottom: 10px;
        }
        #pdf-header p {
          
          margin: 0;
        }
        #pdf-header img {
          height: 75px;
        }
        table {
          border-collapse: collapse;
          width: 100%;
          font-size: 12px;
        }
        th, td {
          border: 1px solid #ddd;
          padding: 6px 8px;
          font-size: 11px;
        }
        th {
          background: #f7f7f7;
          font-weight: 600;
        }
        input, button, .btn {
          display: none !important;
        }
        #quote-form-wrapper { 
          display: none !important; 
        }
        .whitespace-nowrap {
          white-space: nowrap !important;
        }
        .stock-cell {
          white-space: nowrap;
        }
        .original-total {
          text-decoration: line-through;
          font-size: 0.9em;
        }
      `
    });

    window.onafterprint = cleanupPreviews;
    window.onfocus = cleanupPreviews;
  });
});