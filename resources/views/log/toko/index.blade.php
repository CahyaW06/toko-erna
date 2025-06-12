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
                      <th>Laba Kotor</th>
                      <th>Pengeluaran</th>
                      <th>Belanja Modal</th>
                      <th>Laba Bersih</th>
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
                      <th></th>
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
    paging: true,
    lengthMenu: [10, 25, 50, 100, 500],
    ajax: {
      url: "{{ route('log.toko.get') }}",
      type: 'POST', // Gunakan metode POST
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
      },
    },
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'tahun', name: 'tahun' },
      { data: 'bulan', name: 'bulan' },
      { data: 'omset', name: 'omset' },
      { data: 'kotor', name: 'kotor' },
      { data: 'pengeluaran', name: 'pengeluaran' },
      { data: 'belanja_modal', name: 'belanja_modal' },
      { data: 'bersih', name: 'bersih' },
      { data: 'aksi', name: 'aksi' },
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
              columns:  [0, 1, 2, 3, 4, 5, 6], // Ekspor semua kolom yang terlihat
              format: {
                body: function (data, row, column, node) {
                  // Jika kolom nominal (kolom ke-8) berformat Rp, ubah jadi angka biasa
                  if (column >= 3) {
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
