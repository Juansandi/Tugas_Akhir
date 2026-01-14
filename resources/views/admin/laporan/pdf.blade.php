<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
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
        .text-center {
            text-align: center;
        }
        .muted {
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <h2>LAPORAN PENJUALAN</h2>

    <div class="subtitle">
        Total penjualan bersih (setelah dikurangi refund yang disetujui)
    </div>

    <h4>
        Periode:
        {{ $startDate->format('d M Y') }}
        –
        {{ $endDate->format('d M Y') }}
    </h4>

    <table>
        <thead>
            <tr>
                <th width="40">No</th>
                <th width="90">ID Pesanan</th>
                <th width="90">Tanggal</th>
                <th>Total Kotor (Rp)</th>
                <th>Refund (Rp)</th>
                <th>Total Bersih (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandKotor = 0;
                $grandRefund = 0;
                $grandBersih = 0;
            @endphp

            @foreach ($pesanans as $index => $pesanan)
                @php
                    $refund = $pesanan->refund && $pesanan->refund->status === 'disetujui'
                        ? $pesanan->refund->refund_amount
                        : 0;

                    $bersih = $pesanan->total - $refund;

                    $grandKotor += $pesanan->total;
                    $grandRefund += $refund;
                    $grandBersih += $bersih;
                @endphp

                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">#{{ $pesanan->id }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d-m-Y') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($pesanan->total, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ $refund > 0 ? number_format($refund, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right">
                        {{ number_format($bersih, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL</th>
                <th class="text-right">
                    {{ number_format($grandKotor, 0, ',', '.') }}
                </th>
                <th class="text-right">
                    {{ number_format($grandRefund, 0, ',', '.') }}
                </th>
                <th class="text-right">
                    {{ number_format($grandBersih, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <p class="muted" style="margin-top:25px;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>

</body>
</html>
