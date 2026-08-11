// Shared "download this Excel price export" handler for the dashboard's Quick
// Links buttons and profile card download link. The export takes ~15-25s to
// generate server-side, so this gives visible feedback instead of leaving the
// trigger looking unresponsive for that whole wait.

// Fixed banner warning not to leave the page, shown for as long as any
// download triggered by this handler is in flight. Built once and reused by
// every button on the page, rather than duplicating markup per-button.
var gwsPriceExportActiveCount = 0;

function gwsGetPriceExportNotice() {
  var notice = document.getElementById('price-export-notice');
  if (notice) return notice;

  notice = document.createElement('div');
  notice.id = 'price-export-notice';
  notice.setAttribute('role', 'status');
  notice.setAttribute('aria-live', 'polite');
  notice.textContent = "Preparing your file — this can take up to 30 seconds. Please don't leave this page.";
  notice.style.cssText = [
    'position:fixed',
    'top:1rem',
    'left:50%',
    'transform:translateX(-50%) translateY(-150%)',
    'z-index:2000000',
    'max-width:90vw',
    'background:#1a3a5c',
    'color:#d1e3fa',
    'padding:0.85rem 1.5rem',
    'border-radius:4px',
    'font-size:14px',
    'font-weight:600',
    'text-align:center',
    'box-shadow:0 8px 24px rgba(0,0,0,.35)',
    'transition:transform .3s ease'
  ].join(';');
  document.body.appendChild(notice);
  return notice;
}

function gwsShowPriceExportNotice() {
  gwsPriceExportActiveCount++;
  gwsGetPriceExportNotice().style.transform = 'translateX(-50%) translateY(0)';
}

function gwsHidePriceExportNotice() {
  gwsPriceExportActiveCount = Math.max(0, gwsPriceExportActiveCount - 1);
  if (gwsPriceExportActiveCount === 0) {
    gwsGetPriceExportNotice().style.transform = 'translateX(-50%) translateY(-150%)';
  }
}

function bindPriceExportDownload(el, getUrl) {
  const defaultHtml = el.innerHTML;

  el.addEventListener('click', function (e) {
    e.preventDefault();
    const url = getUrl();

    el.setAttribute('aria-disabled', 'true');
    el.classList.add('opacity-60', 'pointer-events-none');
    el.innerHTML = 'Preparing your file<i class="fa-solid fa-spinner fa-spin ms-2"></i>';
    gwsShowPriceExportNotice();

    fetch(url)
      .then(function (response) {
        if (!response.ok) throw new Error('Export request failed: ' + response.status);
        const disposition = response.headers.get('Content-Disposition') || '';
        const match = disposition.match(/filename="([^"]+)"/);
        const filename = match ? match[1] : 'gws-parts-pricing.xlsx';
        return response.blob().then(function (blob) {
          return { blob: blob, filename: filename };
        });
      })
      .then(function (result) {
        const blobUrl = URL.createObjectURL(result.blob);
        const link = document.createElement('a');
        link.href = blobUrl;
        link.download = result.filename;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(blobUrl);
      })
      .catch(function (err) {
        console.error('Price sheet download error:', err);
        alert('Something went wrong preparing your price sheet. Please try again.');
      })
      .finally(function () {
        el.removeAttribute('aria-disabled');
        el.classList.remove('opacity-60', 'pointer-events-none');
        el.innerHTML = defaultHtml;
        gwsHidePriceExportNotice();
      });
  });
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('a.price-export-link[href]').forEach(function (el) {
    bindPriceExportDownload(el, function () {
      return el.getAttribute('href');
    });
  });
});
