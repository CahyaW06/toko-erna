<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Retail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Agar lebar tabel tetap */
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid black;
            word-wrap: break-word; /* Agar teks bisa wrap di dalam sel */
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Data Retail</h2>
    <table class="table table-hover table-bordered" id="data-table">
        <thead>
          <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Alamat</th>
              @foreach ($barangs as $barang)
                  <th>{{ $barang->nama }}</th>
              @endforeach
          </tr>
        </thead>
        <tbody>
            @foreach ($retails as $retail)
            <tr>
                <td>{{ $loop->index }}</td>
                <td>{{ $retail->nama }}</td>
                <td>{{ $retail->alamat }}</td>
                @foreach ($barangs as $barang)
                    <th>{{ $retail->barangs->firstWhere('id', $barang->id)->pivot->jumlah }}</th>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


