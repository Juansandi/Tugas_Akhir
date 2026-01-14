<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <style>
            body{font-family:DejaVu Sans;font-size:11px}
            table{width:100%;border-collapse:collapse}
            th,td{border:1px solid #333;padding:6px}
            th{background:#f0f0f0}
            .text-right{text-align:right}
        </style>
    </head>
    
    <body>
        <h3 style="text-align:center">LAPORAN REFUND</h3>
        <p style="text-align:center">
            {{ $startDate->format('d M Y') }} –
            {{ $endDate->format('d M Y') }}
        </p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Refund</th>
                    <th>Pesanan</th>
                    <th>Customer</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refunds as $i=>$r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>#{{ $r->id }}</td>
                        <td>#{{ $r->pesanan->id }}</td>
                        <td>{{ $r->pengguna->username }}</td>
                        <td class="text-right">
                            Rp {{ number_format($r->refund_amount,0,',','.') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->approved_at)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="4">TOTAL REFUND</th>
                    <th colspan="2" class="text-right">
                        Rp {{ number_format($totalRefund,0,',','.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <p style="margin-top:20px">
        Dicetak: {{ now()->format('d M Y H:i') }}
        </p>

    </body>
</html>
