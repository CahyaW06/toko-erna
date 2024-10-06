@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h3 class="card-title">Data Gudang</h3>
              </div>
              <div class="col d-flex justify-content-end gap-1 me-3 mb-2">
                <a href="{{ route('gudang.create') }}" type="button" class="btn btn-outline-success btn-md">Tambah Barang</a>
              </div>
            </div>
            <p class="card-description">
              Berikut ini keterangan barang di gudang.
            </p>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="model-datatables">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Harga</th>
                      <th>Stok Gudang</th>
                      <th>Terakhir Update</th>
                      <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($barangs as $barang)
                  <tr>
                      <td>{{ $barangs->firstItem() + $loop->index }}</td>
                      <td>{{ $barang->kode_barang }}</td>
                      <td>{{ $barang->nama }}</td>
                      <td>@currency($barang->harga)</td>
                      <td>@number($barang->jumlah) pcs</td>
                      <td>{{ Carbon\Carbon::parse($barang->updated_at)->format('d M Y') }}</td>
                      <td>
                        <form action="{{ route('gudang.destroy', ['gudang' => $barang->id]); }}" method="POST" class="d-flex gap-1">
                          @csrf
                          @method('DELETE')
                          <a href="{{ route('gudang.edit', ['gudang' => $barang->id]); }}" type="button" class="btn btn-outline-warning btn-sm">
                              <i class="mdi mdi-lead-pencil"></i>
                          </a>
                          <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus item {{ $barang->nama }}?')">
                              <i class="mdi mdi-delete"></i>
                          </button>
                        </form>
                      </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                      <th></th>
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
        <div class="card-footer">
          {{ $barangs->links() }}
        </div>
      </div>
    </div>
</div>

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
