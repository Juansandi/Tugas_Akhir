@extends('layouts.admin')
<style>
    .pagination {
        font-size: 0.875rem;
    }

    .pagination .page-link {
        padding: 0.375rem 0.75rem;
    }
</style>
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Notifikasi Admin</h4>
        <form action="{{ route('admin.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-success">
                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    @forelse ($notifications as $notif)
        <div class="card shadow-sm mb-3 {{ $notif->is_read ? '' : 'border border-warning' }}">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div class="me-3">
                    <div class="d-flex align-items-center mb-1">
                        @php
                            $icons = [
                                'pesanan_baru' => 'bi-cart-plus',
                                'stok_hampir_habis' => 'bi-exclamation-triangle',
                                'pesanan_diterima_user' => 'bi-check-circle',
                                'refund_diajukan' => 'bi-arrow-counterclockwise',
                            ];
                            $icon = $icons[$notif->tipe] ?? 'bi-bell';
                        @endphp
                        <i class="bi {{ $icon }} me-2 text-primary"></i>
                        <strong class="text-capitalize">{{ str_replace('_', ' ', $notif->tipe) }}</strong>
                        @if(!$notif->is_read)
                            <span class="badge bg-warning text-dark ms-2">Belum Dibaca</span>
                        @endif
                    </div>
                    <div>{{ $notif->pesan }}</div>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <div>
                    <a href="{{ route('admin.notifications.read', $notif->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Lihat
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            Tidak ada notifikasi.
        </div>
    @endforelse

    <div class="mt-4">
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
