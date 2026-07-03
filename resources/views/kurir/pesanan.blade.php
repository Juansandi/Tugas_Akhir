@extends('layouts.kurir')

@section('title', 'Pesanan Aktif')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Pesanan Aktif</h3>
    <p class="text-muted mb-0">
        Daftar pesanan yang sedang menjadi tanggung jawab Anda.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">ID Pesanan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Jadwal Kirim</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tugas as $item)
                @php
                    $chat = $item->pesanan->chatKurir;
                    $unread = $chat
                        ? $chat->messages
                            ->where('sender_type', 'user')
                            ->where('is_read', false)
                            ->count()
                        : 0;
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">Pesanan #{{ $item->pesanan->id }}</td>
                    <td class="text-center">
                        @switch($item->pesanan->status)
                            @case('dikirim')
                                <span class="badge bg-info text-dark">Dikirim</span>
                                @break

                            @case('diterima')
                                <span class="badge bg-primary text-dark">Diterima</span>
                                @break

                            @case('selesai')
                                <span class="badge bg-success">Selesai</span>
                                @break

                            @default
                                <span class="badge bg-secondary">
                                    {{ ucfirst($item->pesanan->status) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="text-center">
                        @if($item->pesanan->deliverySlot)
                            <span class="badge bg-info">
                                {{ substr($item->pesanan->deliverySlot->waktu_mulai,0,5) }}
                                –
                                {{ substr($item->pesanan->deliverySlot->waktu_selesai,0,5) }}
                            </span>
                        @else
                            <span class="text-muted">Secepatnya</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 flex-wrap justify-content-center">

                            {{-- DETAIL PESANAN --}}
                            <a href="{{ route('kurir.pesanan.detail', $item->id) }}"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-seam"></i> Detail
                            </a>

                            {{-- CHAT --}}
                            @if($chat)
                                <a href="{{ route('kurir.chat.show', $chat->id) }}"
                                class="btn btn-sm btn-outline-success position-relative">
                                    <i class="bi bi-chat-dots"></i> Pesan

                                    @if($unread > 0)
                                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                            {{ $unread }}
                                        </span>
                                    @endif
                                </a>
                            @else
                                <span class="text-muted">Belum tersedia</span>
                            @endif

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
