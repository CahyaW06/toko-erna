@extends('index')

@section('main-panel')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <h4 class="card-title">Data Gudang</h4>
            <p class="card-description">
              Berikut ini keterangan stok di gudang.
            </p>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Harga</th>
                      <th>Jumlah</th>
                      <th>Terakhir Update</th>
                      <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($barangs as $barang)
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $barang->kode_barang }}</td>
                      <td>{{ $barang->nama }}</td>
                      <td>@currency($barang->harga)</td>
                      <td>{{ $barang->jumlah }}</td>
                      <td>{{ Carbon\Carbon::parse($barang->updated_at)->format('d M Y') }}</td>
                      <td>
                        <form action="{{ route('gudang.destroy', ['gudang' => $barang->id]); }}" method="POST" class="d-flex">
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
              </table>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
