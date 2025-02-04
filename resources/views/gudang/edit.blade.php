@extends('index')

@section('main-panel')
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Data Barang</h4>
          <p class="card-description">
            Silahkan masukkan data barang yang baru
          </p>
          <form class="forms-sample" action="{{ route('stok.gudang.update', ['gudang' => $barang->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="kode">Kode Barang</label>
              <input type="text" class="form-control" id="kode" name="kode_barang" placeholder="Kode Barang" value="{{ $barang->kode_barang }}" required>
            </div>
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ $barang->nama }}" required>
            </div>
            <div class="form-group">
              <label for="jumlah">Stok Gudang</label>
              <input type="integer" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" value="{{ $barang->jumlah }}" required>
            </div>
            <div class="form-group">
              <label for="harga">Harga</label>
              <input type="integer" class="form-control" id="harga" name="harga" placeholder="Harga" value="{{ $barang->harga }}" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2" onclick="return confirm('Apakah data sudah valid?')">Terapkan</button>
            </div>
          </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        if ($("#harga").val().length > 3) {
          var n = parseInt($("#harga").val().replace(/\D/g,''),10);
          $("#harga").val(n.toLocaleString("id-ID"));
        }
        if ($("#jumlah").val().length > 3) {
          var n = parseInt($("#jumlah").val().replace(/\D/g,''),10);
          $("#jumlah").val(n.toLocaleString("id-ID"));
        }
        $("#jumlah").keyup(function (e) {
            e.preventDefault();
            if ($(this).val().length > 3) {
                var n = parseInt($(this).val().replace(/\D/g,''),10);
                $(this).val(n.toLocaleString("id-ID"));
            }
        });
        $("#harga").keyup(function (e) {
            e.preventDefault();
            if ($(this).val().length > 3) {
                var n = parseInt($(this).val().replace(/\D/g,''),10);
                $(this).val(n.toLocaleString("id-ID"));
            }
        });
    });
</script>
@endsection
