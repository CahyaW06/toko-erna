@extends('index')

@section('main-panel')
<div class="card flex-grow-1" id="laporan">
    <div class="card-body">
        <div class="d-flex flex-xl-row flex-column justify-content-xl-between align-items-baseline mb-3 gap-4 py-3">
          <div>
            <h4 class="card-title">Detail Konsinyasi per Item</h4>
            <p class="card-description">
              Berikut ini rekap konsinyasi per fraktur dengan rincian setiap barang yang ada.
            </p>
          </div>
          <div>
            <button type="button" class="btn btn-warning w-100" id="export-pdf">Export PDF</button>
          </div>
        </div>
        <div>
        @foreach ($dataRetails as $dataRetail)
          <h5 class="my-3 mb-4">{{ $dataRetail['retail_name'] }}</h5>
          <div class="table-responsive mt-2">
              <table class="table table-hover" id="prev-item-table">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Omset</th>
                        <th class="text-end">PL</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($dataRetail['dataPerRetail'] as $data)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $data['kode_barang'] }}</td>
                          <td>{{ $data['nama_barang'] }}</td>
                          <td class="text-end">@number($data['qty'])</td>
                          <td class="text-end">@number($data['omset'])</td>
                          <td class="text-end">@number($data['pl'])</td>
                      </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-end">
                        <span class="fw-bold">Grand Total</span>
                      </td>
                      <td class="text-end">
                        <span class="fw-bold">@number($dataRetail['finalOmset'])</span>
                      </td>
                      <td class="text-end">
                        <span class="fw-bold">@number($dataRetail['finalPl'])</span>
                      </td>
                    </tr>
                  </tfoot>
              </table>
          </div>
        @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
  document.getElementById("export-pdf").addEventListener("click", function () {
    const element = document.getElementById("laporan");
    $('button').hide();

    html2pdf().set({
        margin: 10,
        filename: `Laporan Konsinyasi per Item-${new Date().toISOString().split('T')[0]}.pdf`,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
    }).from(element).save().then(() => {
      $('button').show();
    });
  });
})
</script>
@endpush
