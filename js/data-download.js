// Parts & Pricing data-download page: server-side DataTable + Excel download.
document.addEventListener('DOMContentLoaded', function () {
  const tableEl = document.getElementById('parts-price-table');
  if (!tableEl) return;

  const table = new DataTable('#parts-price-table', {
    processing: true,
    serverSide: true,
    pageLength: 25,
    ajax: {
      url: '/wp-admin/admin-ajax.php',
      type: 'GET',
      data: function (d) {
        d.action = 'gws_parts_price_dt';
      },
    },
  });

  // Sales/admin switching the sitewide tier selector should refresh this table.
  document.addEventListener('tierChanged', function () {
    table.ajax.reload();
  });

  const downloadBtn = document.getElementById('download-xlsx');
  if (downloadBtn) {
    bindPriceExportDownload(downloadBtn, function () {
      const search = table.search();
      return '/wp-admin/admin-post.php?action=gws_parts_price_export&search=' + encodeURIComponent(search);
    });
  }
});
