@extends('index')

@section('main-panel')
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Data Retail</h4>
          <p class="card-description">
            Silahkan masukkan data retail yang baru
          </p>
          <form class="forms-sample" action="{{ route('stok.retail.update', ['retail' => $retail->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ $retail->nama }}" required>
            </div>
            <div class="form-group">
              <label for="alamat">Alamat</label>
              <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" value="{{ $retail->alamat }}" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2" onclick="return confirm('Apakah data sudah valid?')">Terapkan</button>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
