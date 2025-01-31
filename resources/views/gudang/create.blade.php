@extends('index')

@section('main-panel')
<div class="col-lg-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Tambah Barang</h4>
          <p class="card-description">
            Silahkan masukkan data dari barang yang baru
          </p>
          <form class="forms-sample" action="{{ route('stok.gudang.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="kode">Kode Barang</label>
              <input type="text" class="form-control" id="kode" name="kode_barang" placeholder="Kode Barang" required>
            </div>
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
            </div>
            <div class="form-group">
              <label for="harga">Harga</label>
              <input type="integer" class="form-control" id="harga" name="harga" placeholder="Harga" value="0" required>
            </div>
            <div class="form-group">
              <label for="jumlah">Stok Gudang</label>
              <input type="integer" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah" value="0" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2" onclick="return confirm('Apakah data sudah valid?')">Tambahkan</button>
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
        $("#harga").keyup(function (e) {
            e.preventDefault();
            if ($(this).val().length > 3) {
                var n = parseInt($(this).val().replace(/\D/g,''),10);
                $(this).val(n.toLocaleString("id-ID"));
            }
        });
        $("#jumlah").keyup(function (e) {
            e.preventDefault();
            if ($(this).val().length > 3) {
                var n = parseInt($(this).val().replace(/\D/g,''),10);
                $(this).val(n.toLocaleString("id-ID"));
            }
        });
    });
</script>
@endsection
