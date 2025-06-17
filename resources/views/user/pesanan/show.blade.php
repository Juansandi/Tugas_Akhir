@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Detail Pesanan #{{ $pesanan->id }}</h4>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5>Status Pesanan</h5>
            <p class="mb-3"><span class="badge 
                {{ $pesanan->status == 'selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ ucfirst($pesanan->status) }}
            </span></p>

            <h5>Diskon Promo</h5>
            <p>Rp {{ number_format($pesanan->diskon_dari_promo, 0, ',', '.') }}</p>

            <h5>Total Dibayar</h5>
            <p class="fs-5 fw-bold">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Detail Produk</h5>
            <ul class="list-group list-group-flush">
                @foreach($pesanan->detail as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->produk->nama_produk }}</strong> x {{ $item->quantity }}
                        </div>
                        <div>
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </div>

                        @if($pesanan->status == 'selesai')
                            <a href="{{ route('review.form', $item->produk->id) }}" class="btn btn-sm btn-success ms-3">
                                Review
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route('pesanan.history') }}" class="btn btn-primary flex-grow-1">
            Lihat Riwayat Pesanan
        </a>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary flex-grow-1">
            Kembali ke Produk
        </a>
    </div>
</div>
@endsection
