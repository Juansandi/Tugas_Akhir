@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Riwayat Pesanan Saya</h4>

    @if ($pesanans->isEmpty())
        <div class="alert alert-info">
            Belum ada pesanan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanans as $order)
                        @php
                            $badge = match($order->status) {
                                'menunggu konfirmasi' => 'secondary',
                                'diproses'            => 'primary',
                                'dikirim'             => 'info',
                                'diterima'            => 'warning',
                                'selesai'             => 'success',
                                default               => 'light',
                            };
                        @endphp
                        <tr class="text-center">
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if($order->created_at)
                                    {{ $order->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                <small class="text-muted">
                                    {{ $order->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td class="text-start">

                                <a href="{{ route('pesanan.show', $order->id) }}"
                                   class="btn btn-outline-primary btn-sm w-100 mb-1">
                                    Detail Pesanan
                                </a>

                                @if ($order->status === 'selesai')
                                    @if ($order->refund)
                                        <a href="{{ route('refund.show', $order->refund->id) }}"
                                           class="btn btn-outline-info btn-sm w-100">
                                            Detail Refund
                                        </a>
                                    @else
                                        <a href="{{ route('refund.create', ['pesanan_id' => $order->id]) }}"
                                           class="btn btn-outline-warning btn-sm w-100">
                                            Ajukan Refund
                                        </a>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
