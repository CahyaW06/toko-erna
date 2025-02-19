@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h3 class="card-title">Data Rincian Keuangan Retail {{ $retail->nama }}
                  {{-- {{ \Carbon\Carbon::create()->month($logToko->bulan)->format('M') }} {{ $logToko->tahun }} --}}
                </h3>
                <p class="card-description">
                  Berikut ini rincian pemasukan dan pengeluaran retail.
                </p>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="data-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Barang</th>
                      <th>Barang</th>
                      <th>HPP</th>
                      <th>Jumlah Penjualan</th>
                      <th>HPP x Penjualan</th>
                      <th>Omset Penjualan</th>
                      <th>Konsinyasi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                      <th></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
        </div>
        <div class="card-footer d-flex py-3 mx-3 justify-content-around gap-2 flex-column flex-lg-row align-middle">
          <span class="">Total Omset: Rp {{ number_format($omset,0,',','.') }}</span>
          <span class="">Total Konsinyasi: Rp {{ number_format($konsi,0,',','.') }}</span>
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
      url: "{{ route('stok.retail.rincian', ['retail' => $retail->id]) }}",
      type: 'POST', // Gunakan metode POST
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
      },
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'kode_barang', name: 'kode_barang' },
      { data: 'nama', name: 'nama' },
      { data: 'hpp', name: 'hpp' },
      { data: 'jumlah', name: 'jumlah' },
      { data: 'jumlah_x_hpp', name: 'jumlah_x_hpp' },
      { data: 'omset', name: 'omset' },
      { data: 'konsinyasi', name: 'konsinyasi' },
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
