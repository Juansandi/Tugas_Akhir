@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Detail Pesanan <span class="text-muted">#{{ $pesanan->id }}</span></h4>

    {{-- Info Pesanan --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <h5 class="mb-2">Status Pesanan</h5>
                @php
                    $status = $pesanan->status;
                    $badgeClass = match($status) {
                        'menunggu konfirmasi' => 'bg-secondary text-light',
                        'diproses'            => 'bg-primary text-light',
                        'dikirim'             => 'bg-info text-dark',
                        'diterima'            => 'bg-warning text-dark',
                        'selesai'             => 'bg-success text-light',
                        default               => 'bg-light text-dark',
                    };

                    $subtotal = $pesanan->detail->sum(fn($item) => $item->price * $item->quantity);
                    $diskonPromo = $pesanan->diskon_dari_promo ?? 0;
                    $diskonPoin = $pesanan->diskon_dari_poin ?? 0;
                    $totalSetelahDiskon = $subtotal - $diskonPromo - $diskonPoin;
                @endphp
                <span class="badge {{ $badgeClass }} px-3 py-2">{{ ucfirst($status) }}</span>
            </div>

            <h5 class="mb-3">Perincian Harga</h5>
            <ul class="list-unstyled ps-3">
                <li class="mb-1"><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
                <li class="mb-1"><strong>Diskon dari Promo:</strong> -Rp {{ number_format($diskonPromo, 0, ',', '.') }}</li>
                <li class="mb-1"><strong>Diskon dari Poin:</strong> -Rp {{ number_format($diskonPoin, 0, ',', '.') }}</li>
                <li class="mb-1"><strong>Total Setelah Diskon:</strong> Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}</li>
                <li class="mb-1"><strong>Total Dibayar:</strong> <span class="fs-5 fw-bold text-success">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span></li>
            </ul>

            <h5 class="mt-4 mb-2">Info Pengiriman</h5>
            <p>{{ $pesanan->no_resi ?? '-' }}</p>

            @if($pesanan->status === 'selesai')
                <h5 class="mt-4 mb-2">Poin Diperoleh</h5>
                <p>{{ $pesanan->poin_diperoleh }} poin</p>
            @endif

            @if ($pesanan->status === 'dikirim')
                <form method="POST" action="{{ route('pesanan.updateStatus', $pesanan->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="diterima">
                    <button type="submit" class="btn btn-success mt-3">Konfirmasi Diterima</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail Produk --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Produk dalam Pesanan</h5>
            @php
    use App\Models\Review;

    // Buat array ID produk yang sudah direview user untuk pesanan ini
    $userReviewedProdukIds = Review::where('user_id', Auth::id())
        ->where('pesanan_id', $pesanan->id)
        ->pluck('produk_id')
        ->toArray();
@endphp

@foreach($pesanan->detail as $item)
    <div class="d-flex justify-content-between align-items-center border-bottom py-3">

        {{-- INFO KIRI --}}
        <div class="d-flex align-items-center">

            @php
                $image = $item->type === 'paket'
                    ? optional($item->paket)->image
                    : optional($item->produk)->image;
            @endphp

            @if($image)
                <img src="{{ asset('storage/'.$image) }}"
                     class="me-3"
                     style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
            @endif

            <div>
                @if($item->type === 'paket')
                    <div class="fw-bold">{{ $item->paket->nama_paket }}</div>
                    <small class="text-muted">Paket Produk</small>
                @else
                    <div class="fw-bold">{{ $item->produk->nama_produk }}</div>
                    <small class="text-muted">
                        Ukuran: {{ optional($item->size)->size }}
                    </small>
                @endif

                <div class="text-muted">
                    Jumlah: {{ $item->quantity }}
                </div>
            </div>
        </div>

        {{-- INFO KANAN --}}
        <div class="text-end">
            <div class="fw-semibold">
                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </div>

            {{-- REVIEW HANYA UNTUK PRODUK --}}
            @if(
                $item->type === 'produk' &&
                $pesanan->status === 'selesai' &&
                !in_array($item->produk_id, $userReviewedProdukIds)
            )
                <a href="{{ route('review.form', [
                    'produk' => $item->produk_id,
                    'pesanan_id' => $pesanan->id
                ]) }}"
                class="btn btn-success btn-sm mt-1">
                    Review
                </a>
            @endif
        </div>

    </div>
@endforeach


        </div>
    </div>

    {{-- Tombol Navigasi --}}
    <div class="d-flex flex-column flex-md-row gap-3">
        <a href="{{ route('pesanan.history') }}" class="btn btn-outline-primary w-100">
            Lihat Riwayat Pesanan
        </a>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary w-100">
            Kembali ke Produk
        </a>
    </div>
</div>
@endsection
