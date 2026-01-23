@extends('layouts.admin')

@section('title', 'Notifikasi Admin')

@section('content')
<div class="container py-4" style="max-width: 960px">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-bell me-1"></i> Notifikasi Admin
        </h4>

        @if($notifications->where('is_read', false)->count())
            <form action="{{ route('admin.notifications.readAll') }}" method="POST">
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
            $meta = [
                'pesanan_baru' => [
                    'icon' => 'bi-cart-plus',
                    'color' => 'primary',
                ],
                'stok_hampir_habis' => [
                    'icon' => 'bi-exclamation-triangle',
                    'color' => 'warning',
                ],
                'pesanan_diterima_user' => [
                    'icon' => 'bi-check-circle',
                    'color' => 'success',
                ],
                'refund_diajukan' => [
                    'icon' => 'bi-arrow-counterclockwise',
                    'color' => 'danger',
                ],
                'kurir_selesai_kirim' => [
                    'icon' => 'bi-truck',
                    'color' => 'info',
                ],
            ];

            $icon  = $meta[$notif->tipe]['icon'] ?? 'bi-bell';
            $color = $meta[$notif->tipe]['color'] ?? 'secondary';
        @endphp

        <div class="card mb-3
            {{ $notif->is_read ? 'bg-light' : 'border-'.$color.' bg-'.$color.'-subtle' }}">

            <div class="card-body d-flex gap-3">

                {{-- ICON --}}
                <div class="pt-1">
                    <i class="bi {{ $icon }} fs-4 text-{{ $color }}"></i>
                </div>

                {{-- CONTENT --}}
                <div class="flex-grow-1">

                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong class="text-capitalize">
                            {{ str_replace('_', ' ', $notif->tipe) }}
                        </strong>

                        @if(!$notif->is_read)
                            <span class="badge bg-{{ $color }}">
                                Baru
                            </span>
                        @endif
                    </div>

                    <div class="text-muted mb-1" style="white-space: normal;">
                        {{ $notif->pesan }}
                    </div>

                    <small class="text-muted">
                        {{ $notif->created_at->diffForHumans() }}
                    </small>
                </div>

                {{-- ACTION --}}
                @if($notif->url)
                    <div class="text-end">
                        <a href="{{ route('admin.notifications.read', $notif->id) }}"
                           class="btn btn-sm btn-outline-{{ $color }}">
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
            <p class="mb-0">Tidak ada notifikasi admin</p>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
