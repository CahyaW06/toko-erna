@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h3 class="card-title">Data Log Transaksi</h3>
                <p class="card-description">
                  Berikut ini daftar transaksi pembelian/ganti rugi barang di seluruh retail.
                </p>
              </div>
              <div class="col d-flex justify-content-end gap-1 me-3 mb-2 h-50">
                <a href="{{ route('log.keuangan.create') }}" type="button" class="btn btn-success btn-md">Tambah Transaksi</a>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="data-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Tanggal Transaksi</th>
                      <th>Retail</th>
                      <th>Alamat</th>
                      <th>Kode Barang</th>
                      <th>Barang</th>
                      <th>Status Transaksi</th>
                      <th>Jumlah</th>
                      <th>Nominal</th>
                      <th>Asal Barang</th>
                      <th>Aksi</th>
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
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th></th>
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
        ajax: "{{ route('log.keuangan.get') }}",
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'created_at', name: 'created_at' },
          { data: 'retail.nama', name: 'retail.nama' },
          { data: 'retail.alamat', name: 'retail.alamat' },
          { data: 'barang.kode_barang', name: 'barang.kode_barang' },
          { data: 'barang.nama', name: 'barang.nama' },
          { data: 'status', name: 'status' },
          { data: 'jumlah', name: 'jumlah' },
          { data: 'nominal', name: 'nominal' },
          { data: 'keterangan', name: 'keterangan' },
          { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
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
                  return formattedDate + '_log_transaksi'; // Nama file yang dihasilkan
                },
                title: 'Data Log Transaksi', // Judul di dalam file Excel
                exportOptions: {
                  columns: ':visible', // Ekspor semua kolom yang terlihat
                  format: {
                    body: function (data, row, column, node) {
                      // Jika kolom nominal (kolom ke-8) berformat Rp, ubah jadi angka biasa
                      if (column >= 7) {
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

{{-- Toast --}}
@if (session('success'))
    <div class="toast align-items-center text-bg-success border-0 bottom-0 end-0 position-fixed" role="alert" aria-live="polite" aria-atomic="true" id="liveToast">
    <div class="d-flex">
        <div class="toast-body">
        {{ session('success') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>

    <!-- Progress Bar -->
    <div class="progress" style="height: 5px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" id="toastProgressBar"></div>
    </div>
    </div>
    @elseif (session('error'))
    <div class="toast align-items-center text-bg-danger border-0 bottom-0 end-0 position-fixed" role="alert" aria-live="polite" aria-atomic="true" id="liveToast">
    <div class="d-flex">
        <div class="toast-body">
        {{ session('error') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>

    <!-- Progress Bar -->
    <div class="progress" style="height: 5px;">
        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" id="toastProgressBar"></div>
    </div>
    </div>
@endif

<script>
    $(document).ready(function () {
    var toastEl = document.getElementById('liveToast');
    var toastProgressBar = document.getElementById('toastProgressBar');

    if (toastEl) {
        var toast = new bootstrap.Toast(toastEl, { delay: 5000 }); // Set toast muncul selama 5 detik
        toast.show();

        // Inisialisasi progress bar (5 detik)
        var totalDuration = 5000; // Durasi total dalam milidetik (5 detik)
        var intervalTime = 50; // Interval waktu untuk memperbarui progress
        var decrement = 100 / (totalDuration / intervalTime); // Pengurangan per interval

        var width = 100; // Awal width progress bar
        var progressInterval = setInterval(function () {
        width -= decrement;
        toastProgressBar.style.width = width + "%";

        if (width <= 0) {
            clearInterval(progressInterval);
        }
        }, intervalTime);
    }
    });
</script>

@endsection
