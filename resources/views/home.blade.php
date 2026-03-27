@extends('layouts.app')

@section('hero')

{{-- ================= HERO SECTION ================= --}}
<div class="container-fluid px-0">
    <div class="hero-section text-white d-flex align-items-center"
         style="background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
                url('https://rsud.bulelengkab.go.id/uploads/konten/32_manfaat-sayur-untuk-anak-menunjang-tumbuh-kembang-yang-optimal.jpg')
                center / cover no-repeat;
                min-height: 95vh;">
        <div class="container text-center">

            @guest
                <h1 class="display-4 fw-bold mb-3">
                    Belanja Bahan Pokok Tanpa Keluar Rumah
                </h1>
                <p class="lead mb-4">
                    Beras & sayur segar langsung dari petani
                </p>
                <a href="{{ route('login') }}" class="btn btn-success btn-lg px-4">
                    Masuk & Mulai Belanja
                </a>
            @endguest

            @auth
                <h1 class="display-5 fw-bold mb-3">
                    Halo, {{ auth()->user()->username }} 👋
                </h1>
                <p class="lead mb-4">
                    Yuk lanjutkan belanja kebutuhan harianmu
                </p>
                <a href="#featured" class="btn btn-success btn-lg px-4">
                    Lihat Produk
                </a>
            @endauth

        </div>
    </div>
</div>

{{-- ================= KEUNGGULAN ================= --}}
<div class="container py-5">
    <div class="row text-center g-4">
        <div class="col-md-3">
            <i class="bi bi-basket fs-1 text-success"></i>
            <h6 class="fw-bold mt-3">Produk Segar</h6>
            <p class="text-muted small">Langsung dari petani</p>
        </div>
        <div class="col-md-3">
            <i class="bi bi-truck fs-1 text-success"></i>
            <h6 class="fw-bold mt-3">Pengiriman Cepat</h6>
            <p class="text-muted small">Kurir internal</p>
        </div>
        <div class="col-md-3">
            <i class="bi bi-chat-dots fs-1 text-success"></i>
            <h6 class="fw-bold mt-3">Chat Real-time</h6>
            <p class="text-muted small">Admin & Kurir</p>
        </div>
        <div class="col-md-3">
            <i class="bi bi-shield-check fs-1 text-success"></i>
            <h6 class="fw-bold mt-3">Aman & Terpercaya</h6>
            <p class="text-muted small">Transaksi tercatat</p>
        </div>
    </div>
</div>

{{-- ================= PRODUK PILIHAN ================= --}}
<div class="container py-5" id="featured">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">🌟 Produk Pilihan Hari Ini</h3>
        <a href="{{ route('user.products') }}" class="btn btn-outline-success btn-sm">
            Lihat Semua
        </a>
    </div>

    @if($featuredProducts->count())
    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                     class="card-img-top"
                     style="height:180px; object-fit:cover">

                <div class="card-body d-flex flex-column">
                    <h6 class="fw-semibold mb-1">{{ $product->nama_produk }}</h6>

                    <span class="text-success fw-bold mb-2">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </span>

                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            Detail
                        </a>

                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('produk.detail', $product->id) }}"
                            class="btn btn-sm btn-outline-primary w-100">
                                Pilih Ukuran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
        <p class="text-muted">Belum ada produk tersedia.</p>
    @endif
</div>

{{-- ================= PRODUK TERLARIS ================= --}}
@if(isset($produkTerlaris) && $produkTerlaris->count())
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">🏆 Produk Terlaris</h3>
        <span class="text-muted small">Paling banyak dibeli</span>
    </div>

    <div class="row g-4">
        @foreach($produkTerlaris as $product)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm border-0 position-relative">

                {{-- BADGE --}}
                <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                    Best Seller
                </span>

                {{-- IMAGE --}}
                <img src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                     class="card-img-top"
                     style="height:180px; object-fit:cover">

                <div class="card-body d-flex flex-column">
                    <h6 class="fw-semibold mb-1">
                        {{ $product->nama_produk }}
                    </h6>

                    {{-- HARGA DARI SIZE TERMURAH --}}
                    <span class="text-success fw-bold mb-1">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </span>

                    {{-- TOTAL TERJUAL --}}
                    <small class="text-muted mb-2">
                        Terjual {{ $product->total_terjual }} kali
                    </small>

                    <div class="mt-auto">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="btn btn-outline-success btn-sm w-100">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================= PRODUK REKOMENDASI ================= --}}
@auth
@if($recommendedProducts->count())
<div class="container py-5">
    <h3 class="fw-bold mb-4">🎯 Rekomendasi Untuk Kamu</h3>

    <div class="row g-4">
        @foreach($recommendedProducts as $product)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                     class="card-img-top"
                     style="height:180px; object-fit:cover">

                <div class="card-body">
                    <h6 class="fw-semibold">{{ $product->nama_produk }}</h6>
                    <span class="text-success fw-bold">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </span>
                </div>
                <div class="mt-auto d-flex justify-content-between">
                    <a href="{{ route('produk.detail', $product->id) }}"
                       class="btn btn-sm btn-outline-success w-100">
                        Lihat Produk
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endauth

{{-- ================= KATEGORI ================= --}}
<div class="container py-5">
    <h3 class="text-center fw-bold mb-4">📦 Kategori Produk</h3>

    <div class="row justify-content-center g-4">
        @foreach($categories as $kategori)
        <div class="col-md-3">
            <a href="{{ route('produk.index', ['kategori' => $kategori->id]) }}"
               class="text-decoration-none">
                <div class="card shadow-sm text-center h-100 border-0">
                    <div class="card-body">
                        <i class="bi bi-box-seam fs-1 text-success mb-3"></i>
                        <h6 class="fw-semibold text-dark">
                            {{ $kategori->nama_kategori }}
                        </h6>
                        <small class="text-muted">
                            {{ $kategori->products_count }} produk
                        </small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- ================= PESANAN TERAKHIR ================= --}}
@auth
@if($lastOrder)
<div class="container py-5">
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <strong>Pesanan Terakhir:</strong>
            #{{ $lastOrder->id }}
            <span class="badge bg-secondary ms-2">
                {{ ucfirst($lastOrder->status) }}
            </span>
        </div>
        <a href="{{ route('pesanan.show', $lastOrder->id) }}"
           class="btn btn-sm btn-outline-primary">
            Lihat Pesanan
        </a>
    </div>
</div>
@endif
@endauth

@endsection
