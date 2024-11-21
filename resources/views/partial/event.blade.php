<div class="card card-rounded h-100">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="card-title card-title-dash">Stok Gudang Berdasarkan Jumlah Paling Sedikit</h4>
      </div>
      <div class="mt-4">
        <ul class="bullet-line-list">
          @foreach ($stokGudang as $value)
          <li>
            <div class="d-flex justify-content-between">
              <div><span class="text-light-green">{{ $value->nama }}</span></div>
              <p>{{ $value->jumlah }}</p>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="list align-items-center pt-3">
        <div class="wrapper w-100">
          <p class="mb-0">
            <a href="{{ route('stok.gudang.index') }}" class="fw-bold text-primary">Lihat Stok Seluruhnya<i class="mdi mdi-arrow-right ms-2"></i></a>
          </p>
        </div>
      </div>
    </div>
  </div>
