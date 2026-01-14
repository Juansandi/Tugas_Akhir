<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 5px; }
        .periode { text-align: center; margin-bottom: 20px; }
        .pesanan { margin-bottom: 18px; }
        .header { font-weight: bold; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; }
        .right { text-align: right; }
        .line { border-bottom: 1px dashed #999; margin: 8px 0; }
    </style>
</head>
<body>

<h2>LAPORAN DETAIL PENJUALAN</h2>
<p style="font-size:10px;color:#666;">
    Catatan: Detail penjualan ini belum dikurangi refund.
</p>

<div class="periode">
    @if(request('start_date') || request('end_date'))
        Periode:
        {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
    @else
        Periode: Semua Transaksi
    @endif
</div>

@php
    $currentPesanan = null;
    $grandTotal = 0;
@endphp

@foreach($detailPenjualan as $row)
    @if($currentPesanan !== $row->pesanan_id)
        @if($currentPesanan !== null)
            <div class="right"><strong>Subtotal Pesanan: Rp {{ number_format($subtotalPesanan,0,',','.') }}</strong></div>
            <div class="line"></div>
        @endif

        @php
            $currentPesanan = $row->pesanan_id;
            $subtotalPesanan = 0;
        @endphp

        <div class="pesanan">
            <div class="header">
                Pesanan #{{ $row->pesanan_id }} <br>
                Tanggal: {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}
            </div>
    @endif

    @php
        $subtotalPesanan += $row->subtotal;
        $grandTotal += $row->subtotal;
    @endphp

    <table>
        <tr>
            <td>- {{ $row->nama_item }} ({{ ucfirst($row->type) }})</td>
            <td class="right">
                {{ $row->quantity }} × Rp {{ number_format($row->price,0,',','.') }}
            </td>
            <td class="right">
                Rp {{ number_format($row->subtotal,0,',','.') }}
            </td>
        </tr>
    </table>
@endforeach

@if($currentPesanan !== null)
    <div class="right"><strong>Subtotal Pesanan: Rp {{ number_format($subtotalPesanan,0,',','.') }}</strong></div>
@endif

<hr>
<h3 class="right">TOTAL PENJUALAN: Rp {{ number_format($grandTotal,0,',','.') }}</h3>

<p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>

</body>
</html>
