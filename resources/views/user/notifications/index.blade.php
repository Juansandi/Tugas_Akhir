@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Notifikasi Saya</h4>

    <form action="{{ route('user.notifications.readAll') }}" method="POST" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-success">
            <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
        </button>
    </form>

    @forelse ($notifications as $notif)
        <div class="card mb-3 {{ $notif->is_read ? '' : 'border border-warning' }}">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="mb-1">
                        <strong class="text-capitalize">{{ str_replace('_', ' ', $notif->tipe) }}</strong>
                        @if (!$notif->is_read)
                            <span class="badge bg-warning text-dark ms-2">Baru</span>
                        @endif
                    </div>
                    <div>{{ $notif->pesan }}</div>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <div>
                    <a href="{{ route('user.notifications.read', $notif->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye"></i> Detail
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
        {{ $notifications->links() }}
    </div>
</div>
@endsection
