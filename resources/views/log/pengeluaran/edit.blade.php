@extends('index')

@section('main-panel')
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Data Log</h4>
          <p class="card-description">
            Silahkan masukkan data log yang baru
          </p>
          <form class="forms-sample" action="{{ route('log.pengeluaran.update', ['pengeluaran' => $log->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" placeholder="tanggal" value="{{ \Carbon\Carbon::parse($log->created_at)->format("Y-m-d") }}" required>
            </div>
            <div class="form-group">
              <label for="nama">Rincian</label>
              <input type="text" class="form-control form-control-sm" name="nama" id="nama" placeholder="Rincian" value="{{ $log->nama }}" required>
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
