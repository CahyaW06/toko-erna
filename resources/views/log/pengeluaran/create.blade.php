@extends('index')

@section('main-panel')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Tambah Log Pengeluaran</h4>
        <p class="card-description">
          Silahkan masukkan log pengeluaran
        </p>
        <form class="forms-sample" action="{{ route('log.pengeluaran.store') }}" method="POST">
          @csrf
          <div id="form-container">
            <div class="d-lg-flex gap-2 align-items-center mt-3 border-bottom pb-4 pb-lg-0 row-input">
              <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" class="form-control form-control-sm" name="tanggal[]" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
              </div>
              <div class="form-group col-lg-4">
                <label for="nama">Rincian</label>
                <input type="text" class="form-control form-control-sm" name="nama[]" placeholder="Rincian" required>
              </div>
              <div class="form-group">
                <label for="nominal">Nominal</label>
                <input type="integer" class="form-control form-control-sm nominal angka" name="nominal[]" placeholder="Nominal" required>
              </div>
              <button type="button" class="btn btn-danger btn-sm remove-row ms-1 d-flex align-items-center"><i class="mdi mdi-delete"></i><span class="ms-1">Hapus</span></button>
            </div>
          </div>
          <div class="d-md-flex gap-2 align-items-baseline justify-content-between mt-5 mt-lg-0">
            <span class="">Total: <span class="display-5 text-bg-success px-3 rounded ms-1">Rp <span id="total-nota"></span></span></span>
            <div class="d-flex gap-1 mt-2">
              <button type="button" id="add-row" class="btn btn-warning mt-3">Tambah</button>
              <button type="submit" class="btn btn-success mt-3">Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
      // Fungsi untuk update total-nominal
      function updateTotal() {
        let total = 0;
        $(".nominal").each(function () {
          total += parseInt($(this).val().replace(/[.]/g, ''));
        });
        $("#total-nota").text(total.toLocaleString("id-ID"));
      }

      // Tambahkan event listener untuk format angka
      function formatNumberInputs() {
          $(".angka").each(function () {
              $(this).on('keyup', function (e) {
                  e.preventDefault();
                  if ($(this).val().length > 3) {
                      var n = parseInt($(this).val().replace(/\D/g,''),10);
                      $(this).val(n.toLocaleString("id-ID"));
                  }

                  updateTotal();
              });
          });
      }

      // Menambahkan row baru saat tombol 'Tambah Row' diklik
      $('#add-row').click(function () {
          var newRow = $('.row-input:last').clone();

          $('#form-container').append(newRow);
          formatNumberInputs(); // Tambahkan kembali event listener ke input baru
          updateTotal();
      });

      // Menghapus row saat tombol 'Hapus' diklik
      $(document).on('click', '.remove-row', function () {
          if ($('.remove-row').length > 1) {
              $(this).closest('.row-input').remove(); // Hapus row
          } else {
              alert("Minimal satu row input harus ada.");
          }
      });

      // Terapkan format angka pada input jumlah dan nominal
      formatNumberInputs();
  });
</script>
@endsection
