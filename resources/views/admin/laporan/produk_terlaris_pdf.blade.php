<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk Terlaris</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<h3>Laporan Produk Terlaris</h3>

<p>
    Periode:
    {{ $startDate->format('d M Y') }}
    -
    {{ $endDate->format('d M Y') }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Ukuran</th>
            <th>Kategori</th>
            <th>Total Terjual</th>
            <th>Total Omzet</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produkTerlaris as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->ukuran }}</td>
                <td>{{ $item->nama_kategori }}</td>
                <td>{{ $item->total_qty }}</td>
                <td>
                    Rp {{ number_format($item->total_omzet, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
