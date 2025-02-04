@extends('index')

@section('main-panel')
<div class="col-xl-4">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">Ubah Data Log</h4>
          <p class="card-description">
            Silahkan masukkan data log yang baru
          </p>
          <form class="forms-sample" action="{{ route('log.toko.updateBelanjaModal', ['toko' => $log->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group d-flex justify-content-between">
              <div>
                <label for="tahun">Tahun</label>
                <input type="integer" class="form-control form-control-sm" id="tahun" placeholder="Tahun" value="{{ $log->tahun }}" readonly>
              </div>
              <div>
                <label for="bulan">Bulan</label>
                <input type="integer" class="form-control form-control-sm" id="bulan" placeholder="Bulan" value="{{ $log->bulan }}" readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="omset">Omset</label>
              <input type="integer" class="form-control form-control-sm angka" id="omset" placeholder="Omset" value="{{ $log->omset }}" readonly>
            </div>
            <div class="form-group">
              <label for="kotor">Laba Kotor</label>
              <input type="integer" class="form-control form-control-sm angka" id="kotor" placeholder="Laba Kotor" value="{{ $log->kotor }}" readonly>
            </div>
            <div class="form-group">
              <label for="pengeluaran">Pengeluaran</label>
              <input type="integer" class="form-control form-control-sm angka" id="pengeluaran" placeholder="Pengeluaran" value="{{ $log->pengeluaran }}" readonly>
            </div>
            <div class="form-group">
              <label for="belanja_modal">Belanja Modal</label>
              <input type="integer" class="form-control form-control-sm angka" name="belanja_modal" id="belanja_modal" placeholder="Belanja Modal" value="{{ $log->belanja_modal }}">
            </div>
            <div class="form-group">
              <label for="bersih">Laba Bersih</label>
              <input type="integer" class="form-control form-control-sm angka" id="bersih" placeholder="Laba Bersih" value="{{ $log->bersih }}" readonly>
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
      $(".angka").each(function () {
        var n = parseInt($(this).val().replace(/\D/g,''),10);
        $(this).val(n.toLocaleString("id-ID"));
      });
      $(".angka").keyup(function (e) {
          e.preventDefault();
          if ($(this).val().length > 3) {
              var n = parseInt($(this).val().replace(/\D/g,''),10);
              $(this).val(n.toLocaleString("id-ID"));
          }
      });
    });
</script>
@endsection
