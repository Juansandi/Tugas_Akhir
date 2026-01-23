@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container py-4" style="max-width: 900px">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-bell me-1"></i> Notifikasi Saya
        </h4>

        @if($notifications->where('is_read', false)->count())
            <form action="{{ route('user.notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- LIST NOTIFIKASI --}}
    @forelse ($notifications as $notif)

        @php
            $icons = [
                'pesanan_diproses'  => 'bi-gear',
                'pesanan_dikirim'   => 'bi-truck',
                'pesanan_selesai'   => 'bi-check-circle',
                'refund_disetujui'  => 'bi-arrow-counterclockwise',
                'refund_ditolak'    => 'bi-x-circle',
                'chat_admin'        => 'bi-chat-dots',
                'chat_kurir'        => 'bi-chat-left-text',
            ];

            $icon = $icons[$notif->tipe] ?? 'bi-bell';
        @endphp

        <div class="card mb-3
            {{ $notif->is_read ? 'bg-light' : 'border-warning bg-warning-subtle' }}">

            <div class="card-body d-flex gap-3">

                {{-- ICON --}}
                <div class="pt-1">
                    <i class="bi {{ $icon }} fs-4 text-success"></i>
                </div>

                {{-- CONTENT --}}
                <div class="flex-grow-1">

                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong class="text-capitalize">
                            {{ str_replace('_', ' ', $notif->tipe) }}
                        </strong>

                        @if(!$notif->is_read)
                            <span class="badge bg-warning text-dark">Baru</span>
                        @endif
                    </div>

                    <div class="text-muted mb-1">
                        {{ $notif->pesan }}
                    </div>

                    <small class="text-muted">
                        {{ $notif->created_at->diffForHumans() }}
                    </small>
                </div>

                {{-- ACTION --}}
                @if($notif->url)
                    <div class="text-end">
                        <a href="{{ route('user.notifications.read', $notif->id) }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                @endif

            </div>
        </div>

    @empty
        {{-- EMPTY STATE --}}
        <div class="text-center text-muted py-5">
            <i class="bi bi-bell-slash fs-1 mb-3"></i>
            <p class="mb-0">Belum ada notifikasi</p>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
