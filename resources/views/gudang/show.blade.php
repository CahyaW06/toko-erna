@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h3 class="card-title">Data Barang {{ $barang->nama }}</h3>
                <p class="card-description">
                  Berikut ini rincian barang {{ $barang->nama }} di seluruh retail yang ada.
                </p>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="data-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Retail</th>
                      <th>Alamat</th>
                      <th>Jumlah Barang</th>
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
  var table = $("#data-table").DataTable({
    ordering: false,
    serverSide: true,
    processing: true,
    scrollX: true,
    scrollY: 400,
    autoWidth: false,
    ajax: {
      url: "{{ route('stok.gudang.rincian', ['gudang' => $barang->id]) }}",
      type: 'POST', // Gunakan metode POST
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
      },
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'retail', name: 'retail' },
      { data: 'alamat', name: 'alamat' },
      { data: 'jumlah', name: 'jumlah' },
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
              return formattedDate + '_barang_retail'; // Nama file yang dihasilkan
            },
            title: 'Data Barang di Retail', // Judul di dalam file Excel
            exportOptions: {
              format: {
                body: function (data, row, column, node) {
                  // Jika kolom nominal (kolom ke-8) berformat Rp, ubah jadi angka biasa
                  if (column >= 2) {
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
});
</script>
@endsection
