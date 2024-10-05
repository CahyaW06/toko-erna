@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
              <div class="col">
                <h4 class="card-title">Data Gudang</h4>
              </div>
              <div class="col d-flex justify-content-end gap-1 me-3">
                <a href="{{ route('gudang.create') }}" type="button" class="btn btn-outline-success btn-md">Tambah Barang</a>
                <button type="button" class="btn btn-outline-success btn-icon-text">
                  Print
                  <i class="ti-printer btn-icon-append"></i>
                </button>
              </div>
            </div>
            <p class="card-description">
              Berikut ini keterangan barang di gudang.
            </p>
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="model-datatables">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Harga</th>
                      <th>Stok Gudang</th>
                      <th>Terakhir Update</th>
                      <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($barangs as $barang)
                  <tr>
                      <td>{{ $barangs->firstItem() + $loop->index }}</td>
                      <td>{{ $barang->kode_barang }}</td>
                      <td>{{ $barang->nama }}</td>
                      <td>@currency($barang->harga)</td>
                      <td>@number($barang->jumlah) pcs</td>
                      <td>{{ Carbon\Carbon::parse($barang->updated_at)->format('d M Y') }}</td>
                      <td>
                        <form action="{{ route('gudang.destroy', ['gudang' => $barang->id]); }}" method="POST" class="d-flex gap-1">
                          @csrf
                          @method('DELETE')
                          <a href="{{ route('gudang.edit', ['gudang' => $barang->id]); }}" type="button" class="btn btn-outline-warning btn-sm">
                              <i class="mdi mdi-lead-pencil"></i>
                          </a>
                          <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus item {{ $barang->nama }}?')">
                              <i class="mdi mdi-delete"></i>
                          </button>
                        </form>
                      </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                      <th></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th><input type="text" class="form-control"></th>
                      <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
        </div>
        <div class="card-footer">
          {{ $barangs->links() }}
        </div>
      </div>
    </div>
</div>

<script>
  let modelDataTables = new DataTable('#model-datatables', {
        "searching": true,
        initComplete: function () {
            // Move the footer to the top of the table
            $(this.api().table().header()).prepend($(this.api().table().footer()).children());
            this.api().columns().every(function () {
                var that = this;

                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                })
            }
        }
    });
</script>
@endsection
