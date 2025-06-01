@extends('index')

@section('head')
<style>
  table {
    page-break-inside: auto;
  }

  tr {
    page-break-inside: avoid;
    page-break-after: auto;
  }

  thead {
    display: table-header-group;
  }

  tfoot {
    display: table-footer-group;
  }

  .no-break {
    page-break-inside: avoid !important;
  }
</style>
@endsection

@section('main-panel')
<div class="card flex-grow-1" id="laporan">
    <div class="card-body">
        <div class="d-flex flex-xl-row flex-column justify-content-xl-between align-items-baseline mb-3 gap-4 py-3">
          <div>
            <h4 class="card-title">Detail Transaksi per Fraktur</h4>
            <p class="card-description">
              Berikut ini rekap transaksi per fraktur, mulai dari total transaksi, total konsinyasi seharusnya, serta total yang belum dibayarkan pada fraktur.
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
                      <th class="text-end">Konsinyasi</th>
                      <th class="text-end">Transaksi</th>
                      <th class="text-end">Sisa Konsinyasi</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($dataRetails as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['retail_name'] }}</td>
                        <td class="text-end">@number($data['total'])</td>
                        <td class="text-end">@number($data['paid'])</td>
                        <td class="text-end">@number($data['due'])</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="2" class="text-end">
                      <span class="fw-bold">Grand Total</span>
                    </td>
                    <td class="text-end">
                      <span class="fw-bold" id="final-total">@number($finalTotal)</span>
                    </td>
                    <td class="text-end">
                      <span class="fw-bold" id="final-paid">@number($finalPaid)</span>
                    </td>
                    <td class="text-end">
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
        filename: `Laporan Transaksi per Fraktur-${new Date().toISOString().split('T')[0]}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
        pageBreak: {
          mode: ['css', 'legacy'],
          avoid: ['tr', '.no-break']
        }
    }).from(element).save().then(() => {
      $('button').show();
    });
  });
})
</script>
@endpush
