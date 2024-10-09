<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Gudang</title>
    <style>
        /* Tambahkan styling untuk PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Gudang</h2>
    <table class="table table-hover table-bordered" id="data-table">
        <thead>
          <tr>
              <th>No</th>
              <th>Kode Barang</th>
              <th>Nama</th>
              <th>Stok Gudang</th>
              <th>Terakhir Update</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $barang)
            <tr>
                <td>{{ $loop->index }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama }}</td>
                <td>@number($barang->jumlah) pcs</td>
                <td>{{ Carbon\Carbon::parse($barang->updated_at)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


