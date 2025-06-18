@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Riwayat Pesanan Saya</h4>

    @if ($pesanan->isEmpty())
        <p>Belum ada pesanan.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal Pesan</th>
                    <th>Status</th>
                    <th>Total Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan as $pesanan)
                <tr>
                    <td>{{ $pesanan->id }}</td>
                    <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                    <td>
                            @php
                                $status = $pesanan->status;
                                $badgeClass = match($status) {
                                    'menunggu konfirmasi' => 'bg-secondary text-light',
                                    'diproses'            => 'bg-primary text-light',
                                    'dikirim'             => 'bg-info text-dark',
                                    'diterima'            => 'bg-warning text-dark',
                                    'selesai'             => 'bg-success text-light',
                                    default               => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('pesanan.show', $pesanan->id) }}" class="btn btn-primary btn-sm">Detail</a>

                        @if ($pesanan->status === 'selesai')
                            @if ($pesanan->refund)
                                <a href="{{ route('refund.show', $pesanan->refund->id) }}" class="btn btn-info btn-sm mt-1">
                                    Detail Refund
                                </a>
                            @else
                                <a href="{{ route('refund.create', ['pesanan_id' => $pesanan->id]) }}" class="btn btn-warning btn-sm mt-1">
                                    Ajukan Refund
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
</div>

@endsection
