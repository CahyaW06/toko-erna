@extends('index')

@section('main-panel')
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Tambah Retail</h4>
          <p class="card-description">
            Silahkan masukkan data dari retail yang baru
          </p>
          <form class="forms-sample" action="{{ route('stok.retail.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2" onclick="return confirm('Apakah data sudah valid?')">Tambahkan</button>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection
