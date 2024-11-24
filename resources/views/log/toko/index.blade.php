@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h3 class="card-title">Data Log Keuangan Toko</h3>
                <p class="card-description">
                  Berikut ini log keuangan toko.
                </p>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="data-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Tahun</th>
                      <th>Bulan</th>
                      <th>Omset</th>
                      <th>Pengeluaran</th>
                      <th>Bersih</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                      <th></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
        </div>
      </div>
    </div>
</div>

{{-- Datatables --}}
<script>
$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "{{ route('log.keuangan.get') }}",
    success: function (response) {
      var table = $("#data-table").DataTable({
        ordering: false,
        serverSide: true,
        processing: true,
        scrollX: true,
        scrollY: 400,
        autoWidth: false,
        ajax: "{{ route('log.toko.get') }}",
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'tahun', name: 'tahun' },
          { data: 'bulan', name: 'bulan' },
          { data: 'omset', name: 'omset' },
          { data: 'pengeluaran', name: 'pengeluaran' },
          { data: 'bersih', name: 'bersih' },
        ],
        layout: {
          topStart: {
            buttons: [
              'pageLength', // Tombol untuk mengatur panjang halaman
              {
                extend: 'excelHtml5',
                text: 'Ekspor ke Excel',
                filename: function() {
                  var date = new Date();
                  var formattedDate = date.getFullYear() + "_" + (date.getMonth() + 1) + "_" + date.getDate();
                  return formattedDate + '_log_toko'; // Nama file yang dihasilkan
                },
                title: 'Data Log Toko', // Judul di dalam file Excel
                exportOptions: {
                  columns: ':visible', // Ekspor semua kolom yang terlihat
                  format: {
                    body: function (data, row, column, node) {
                      // Jika kolom nominal (kolom ke-8) berformat Rp, ubah jadi angka biasa
                      if (column >= 4) {
                        return data.replace(/[Rp.,\s]/g, ''); // Hapus simbol Rp dan koma
                      }
                      return data; // Untuk kolom lain, tetap ekspor apa adanya
                    }
                  }
                }
              }
            ],
          },
        },
        scrollCollapse: true,
        initComplete: function () {
          var api = this.api();

          // Pindahkan footer ke atas tabel
          $(api.table().header()).prepend($(api.table().footer()).children());

          // Penerapan pencarian kolom di input field (tfoot)
          api.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change clear', function () {
              if (that.search() !== this.value) {
                that.search(this.value).draw();
              }
            });
          });

          // Menyesuaikan kolom setelah tabel diinisialisasi
          api.columns.adjust().draw();
        }
      });

      // Pastikan pemanggilan adjust dilakukan setelah tabel diinisialisasi
      table.columns.adjust().draw();
    }
  });
});
</script>
@endsection
