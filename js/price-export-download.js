// Shared "download this Excel price export" handler for the dashboard's Quick
// Links buttons and the data-download page's Download button. The export takes
// ~15-25s to generate server-side, so this gives visible feedback instead of
// leaving the trigger looking unresponsive for that whole wait.
function bindPriceExportDownload(el, getUrl) {
  const defaultHtml = el.innerHTML;

  el.addEventListener('click', function (e) {
    e.preventDefault();
    const url = getUrl();

    el.setAttribute('aria-disabled', 'true');
    el.classList.add('opacity-60', 'pointer-events-none');
    el.innerHTML = 'Preparing your file<i class="fa-solid fa-spinner fa-spin ms-2"></i>';

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
