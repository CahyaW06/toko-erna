@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="d-flex flex-xl-row flex-column align-items-center mb-4 gap-3">
              <div class="flex-grow-1">
                <h3 class="card-title">Data Stok Retail</h3>
                <p class="card-description">
                  Berikut ini daftar dari seluruh retail.
                </p>
              </div>
              <div class="d-flex flex-column flex-xl-row justify-content-end gap-2 h-50">
                <a href="{{ route('stok.retail.create') }}" type="button" class="btn btn-success btn-md">Tambah Retail</a>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="data-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Alamat</th>
                      @foreach ($barangs as $barang)
                        <th>{{ str_replace('_', ' ', $barang->nama) }}</th>
                      @endforeach
                      <th>Konsinyasi</th>
                      <th>Omset</th>
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
                      @foreach ($barangs as $barang)
                        <th></th>
                      @endforeach
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
      var columns = [];
        $.ajax({
          type: "GET",
          url: "{{ route('stok.gudang.get-list-nama') }}",
          success: function (response) {
            columns.push({ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false });
            columns.push({ data: 'nama', name: 'nama' });
            columns.push({ data: 'alamat', name: 'alamat' });

            response.forEach(element => {
              columns.push({ data: element.nama, name: element.nama });
            });

            columns.push({ data: 'konsinyasi', name: 'konsinyasi' });
            columns.push({ data: 'omset', name: 'omset' });
            columns.push({ data: 'aksi', name: 'aksi', orderable: false, searchable: false });

            $("#data-table").DataTable({
                ordering: false,
                serverSide: true,
                processing: true,
                scrollX: true,
                scrollY: 400,
                paging: true,
                lengthMenu: [10, 25, 50, 100, 500],
                ajax: {
                    url: "{{ route('stok.retail.get') }}",
                    type: 'POST', // Gunakan metode POST
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
                    },
                },
                columns: columns,
                // fixedColumns: {
                //     start: 3
                // },
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
                          return formattedDate + '_stok_retail'; // Nama file yang dihasilkan
                        },
                        title: 'Data Stok di Retail', // Judul di dalam file Excel
                        exportOptions: {
                          columns: ':not(:last-child)', // Ekspor semua kolom yang terlihat
                        }
                      }
                    ],
                  },
                },
                scrollCollapse: true,
                initComplete: function () {
                    // Pindahkan footer ke atas tabel
                    $(this.api().table().header()).prepend($(this.api().table().footer()).children());

                    // Penerapan pencarian kolom di input field (tfoot)
                    this.api().columns().every(function () {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function () {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });
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
