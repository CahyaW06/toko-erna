@extends('index')

@section('main-panel')
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Data Log</h4>
          <p class="card-description">
            Silahkan masukkan data log yang baru
          </p>
          <form class="forms-sample" action="{{ route('log.gudang.update', ['gudang' => $log->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" placeholder="tanggal" value="{{ \Carbon\Carbon::parse($log->created_at)->format("Y-m-d") }}" required>
            </div>
            <div class="form-group">
              <label for="barang">Barang</label>
              <div class="d-flex flex-column">
                <select class="form-control form-control-sm text-black" name="barang" id="barang">
                  @foreach ($barangs as $barang)
                  <option @if($barang->id == $log->barang_id) selected @endif value="{{ $barang->id }}">{{ $barang->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <div class="d-flex flex-column">
                <select class="form-control form-control-sm text-black" name="status" id="status">
                  <option @if($log->status == "Masuk") selected @endif value="1">Masuk</option>
                  <option @if($log->status == "Keluar") selected @endif value="2">Keluar</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="jumlah">Jumlah</label>
              <input type="integer" class="form-control form-control-sm" name="jumlah" id="jumlah" placeholder="Jumlah" value="{{ $log->jumlah }}" required>
            </div>
            <div class="form-group">
              <label for="nominal">Nominal</label>
              <input type="integer" class="form-control form-control-sm" name="nominal" id="nominal" placeholder="Nominal" value="{{ $log->nominal }}" required>
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
        if ($("#nominal").val().length > 3) {
          var n = parseInt($("#nominal").val().replace(/\D/g,''),10);
          $("#nominal").val(n.toLocaleString("id-ID"));
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
        $("#nominal").keyup(function (e) {
            e.preventDefault();
            if ($(this).val().length > 3) {
                var n = parseInt($(this).val().replace(/\D/g,''),10);
                $(this).val(n.toLocaleString("id-ID"));
            }
        });
    });
</script>
@endsection
