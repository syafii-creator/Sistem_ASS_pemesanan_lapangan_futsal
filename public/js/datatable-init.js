/**
 * LapanganKita - DataTables Global Auto-Initializer
 * Menerapkan UI table menggunakan DataTable dengan relasi data (JOIN), pagination, filter, dan sort
 */
$(document).ready(function() {
  // Matikan alert dialog browser bawaan DataTables
  if (window.jQuery && $.fn && $.fn.dataTable) {
    $.fn.dataTable.ext.errMode = 'none';
  }

  function initDataTables() {
    $('.datatable, .use-datatable').each(function() {
      if (!$.fn.DataTable.isDataTable(this)) {
        // Jika tbody hanya memiliki 1 row dengan colspan (fallback blade), kosongkan agar DataTables mengaturnya secara native
        var $tbody = $(this).find('tbody');
        if ($tbody.children('tr').length === 1 && $tbody.find('td[colspan]').length > 0) {
          $tbody.empty();
        }

        $(this).DataTable({
          pageLength: 10,
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
          language: {
            search: "Cari data:",
            searchPlaceholder: "Ketik kata kunci...",
            lengthMenu: "Tampilkan _MENU_ baris",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Belum ada data pada tabel ini",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ entri",
            infoEmpty: "Menampilkan 0 s/d 0 dari 0 entri",
            infoFiltered: "(disaring dari _MAX_ entri)",
            paginate: {
              first: "«",
              last: "»",
              next: "›",
              previous: "‹"
            }
          },
          responsive: true,
          order: [],
          drawCallback: function() {
            if (window.lucide) {
              lucide.createIcons();
            }
          }
        });
      }
    });
  }

  // Init on load
  initDataTables();

  // Expose to window for dynamic tab switches
  window.reinitDataTables = initDataTables;
});
