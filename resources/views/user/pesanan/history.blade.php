@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-bold">Riwayat Pesanan Saya</h4>

    @if ($pesanans->isEmpty())
        <div class="alert alert-info">
            Belum ada pesanan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th width="240">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanans as $order)

                        @php
                            $adminUnread = optional($order->chatAdminUnreadForUser)->unread_count ?? 0;
                            $kurirUnread = optional($order->chatKurirUnreadForUser)->unread_count ?? 0;

                            // ===== REFUND WINDOW 24 JAM (ANTI DESIMAL) =====
                            if ($order->status === 'selesai') {
                                $lewatMenit = $order->updated_at->diffInMinutes(now());
                                $sisaJam = max(0, floor((24 * 60 - $lewatMenit) / 60));
                            } else {
                                $sisaJam = null;
                            }
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>

                            {{-- TANGGAL --}}
                            <td>
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small class="text-muted">
                                    {{ $order->created_at->format('H:i') }}
                                </small>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="badge {{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>

                            {{-- TOTAL --}}
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>

                            {{-- AKSI --}}
                            <td class="text-start">

                                {{-- DETAIL --}}
                                <a href="{{ route('pesanan.show', $order->id) }}"
                                   class="btn btn-outline-primary btn-sm w-100 mb-1">
                                    Detail Pesanan
                                </a>

                                {{-- REFUND LOGIC --}}
                                @if($order->status === 'selesai')

                                    {{-- COUNTDOWN --}}
                                    @if($sisaJam > 0 && !$order->refund)
                                        <span class="badge bg-warning text-dark d-block mb-1">
                                            ⏱ Refund tersisa {{ $sisaJam }} jam
                                        </span>
                                    @endif

                                    {{-- TOMBOL --}}
                                    @if(!$order->refund && $sisaJam > 0)
                                        <a href="{{ route('refund.create', ['pesanan_id' => $order->id]) }}"
                                        class="btn btn-outline-warning btn-sm w-100 mb-1">
                                            Ajukan Refund
                                        </a>
                                    @elseif($order->refund)
                                        <a href="{{ route('refund.show', $order->refund->id) }}"
                                        class="btn btn-outline-info btn-sm w-100 mb-1">
                                            Detail Refund
                                        </a>
                                    @endif

                                @endif

                                {{-- NOTIF CHAT --}}
                                @if($adminUnread + $kurirUnread > 0)
                                    <span class="badge bg-danger d-block mt-1">
                                        {{ $adminUnread + $kurirUnread }} pesan baru
                                    </span>
                                @endif

                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
