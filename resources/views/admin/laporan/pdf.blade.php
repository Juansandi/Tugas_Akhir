<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2, h4 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #333;
            padding: 6px;
        }
        th {
            background: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <h2>LAPORAN PENJUALAN</h2>
    <h4>
        Periode:
        {{ $startDate->format('d M Y') }}
        –
        {{ $endDate->format('d M Y') }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesanans as $index => $pesanan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>#{{ $pesanan->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d-m-Y') }}</td>
                    <td class="text-right">
                        {{ number_format($pesanan->total, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">TOTAL PENJUALAN</th>
                <th class="text-right">
                    {{ number_format($total, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top:30px;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>

</body>
</html>
