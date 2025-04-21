@extends('index')

@section('main-panel')
<div class="card flex-grow-1" id="laporan">
    <div class="card-body">
        <div class="d-flex flex-xl-row flex-column justify-content-xl-between align-items-baseline mb-3 gap-4 py-3">
          <div>
            <h4 class="card-title">Detail Konsinyasi per Fraktur</h4>
            <p class="card-description">
              Berikut ini rekap konsinyasi per fraktur, mulai dari total konsinyasi, total yang sudah laku, serta total yang belum laku pada fraktur.
            </p>
          </div>
          <div>
            <button type="button" class="btn btn-warning w-100" id="export-pdf">Export PDF</button>
          </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="prev-item-table">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Outlet</th>
                      <th class="text-start">Total</th>
                      <th class="text-start">Paid</th>
                      <th class="text-start">Due</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($dataRetails as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['retail_name'] }}</td>
                        <td class="">@number($data['total'])</td>
                        <td class="">@number($data['paid'])</td>
                        <td class="">@number($data['due'])</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2" class="text-end">
                      <span class="fw-bold">Grand Total</span>
                    </td>
                    <td>
                      <span class="fw-bold" id="final-total">@number($finalTotal)</span>
                    </td>
                    <td>
                      <span class="fw-bold" id="final-paid">@number($finalPaid)</span>
                    </td>
                    <td>
                      <span class="fw-bold" id="final-due">@number($finalDue)</span>
                    </td>
                  </tr>
                </tfoot>
            </table>
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
        filename: `Laporan Konsinyasi per Fraktur.pdf`,
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        // pageBreak: { mode: ['css', 'legacy'], avoid: ['no-break'] }
    }).from(element).save().then(() => {
      $('button').show();
    });
  });
})
</script>
@endpush
