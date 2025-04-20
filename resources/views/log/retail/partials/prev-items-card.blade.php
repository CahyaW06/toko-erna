<div class="card flex-grow-1" id="prev-item-card">
    <div class="card-body">
      <h4 class="card-title">Detail Konsinyasi <span id="retail-name"></span></h4>
      <p class="card-description">
        Berikut ini daftar barang yang sebelumnya telah dikonsinyasikan beserta transaksi yang sudah dilakukan
      </p>

      <div class="table table-responsive">
        <table class="table table-hover" id="prev-item-table">
          <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Barang</th>
                <th>QtyIn</th>
                <th>QtyOut</th>
                <th>QtyCRet</th>
                <th>Harga</th>
                <th>SubTotal</th>
            </tr>
          </thead>
          <tbody id="prev-item-body" class="fw-normal">
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6"></td>
              <td class="text-start">
                <span class="fw-bold">Total</span>
              </td>
              <td class="">
                <span class="fw-bold" id="prev-total"></span>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="d-flex w-full mt-4 gap-5 align-items-baseline no-print">
        <div class="d-flex flex-column justify-content-start">
          <button type="button" class="btn btn-warning" id="export-pdf">Export PDF</button>
        </div>
      </div>
    </div>
</div>

@push('scripts')
<script>
  $(document).ready(function () {
    function updatePrevKonsiTable() {
      let retailId = $("#retail").val();
      let retailName = "";
      let url = "{{ route('stok.retail.konsi', ['retail' => '__RETAIL_ID__']) }}".replace('__RETAIL_ID__', retailId);

      let prevTableBody = $('#prev-item-body');
      prevTableBody.empty();

      let data = $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
          let total = 0;

          response.forEach((element, index) => {
            total += element.sub_total;

            prevTableBody.append(
              `<tr>
                <td>${index + 1}</td>
                <td>${element.kode_barang}</td>
                <td>${element.barang}</td>
                <td>${element.qty_in.toLocaleString("id-ID")}</td>
                <td>${element.qty_out.toLocaleString("id-ID")}</td>
                <td>
                  ${element.qty_c_ret.toLocaleString("id-ID")}
                  <input name="retur[]" value="${element.qty_c_ret}" class="d-none">
                  <input name="barang_retur[]" value="${element.barang_id}" class="d-none">
                  <input name="nominal_retur[]" value="${element.harga}" class="d-none">
                </td>
                <td>${element.harga.toLocaleString("id-ID")}</td>
                <td>${element.sub_total.toLocaleString("id-ID")}</td>
              </tr>
              `
            );
          });

          $('#prev-total').text(`Rp${total.toLocaleString("id-ID")}`);
        },
        error: function (xhr, status, error) {
          $('#prev-total').text(`Rp0`);

          let message = 'Terjadi kesalahan';

          $('#prev-total').text(`Rp0`);
          prevTableBody.append(
            `<tr>
              <td colspan="8" class="text-center">${message}</td>
            </tr>`
          );
        }
      });
    }

    $('#retail').on('change', function () {
      updatePrevKonsiTable();
      retailName = $('#retail-name').text($(this).find('option:selected').text());
    })

    retailName = $('#retail-name').text($('#retail').find('option:selected').text());
    updatePrevKonsiTable();
  })
</script>
<script>
  $(document).ready(function () {
    document.getElementById("export-pdf").addEventListener("click", function () {
      const element = document.getElementById("prev-item-card");
      $('button').hide();

      html2pdf().set({
          margin: 10,
          filename: `laporan-konsinyasi-${retailName.text().toLowerCase().replace(/\s+/g, '-')}-${new Date().toISOString().split('T')[0]}.pdf`,
          image: { type: 'jpeg', quality: 1 },
          html2canvas: { scale: 10 },
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
          pageBreak: { mode: ['css', 'legacy'], avoid: ['no-break'] }
      }).from(element).save().then(() => {
        $('button').show();
      });
    });
  })
</script>
@endpush
