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
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center p-4 feature-card">
                <div class="mb-3">
                    <i class="bi bi-basket fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold">
                    Produk Segar
                </h5>
                <p class="text-muted small mb-0">
                    Menyediakan bahan pokok berkualitas yang selalu segar.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center p-4 feature-card">
                <div class="mb-3">
                    <i class="bi bi-truck fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold">
                    Pengiriman Cepat
                </h5>
                <p class="text-muted small mb-0">
                    Pengiriman dilakukan oleh kurir internal secara tepat waktu.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center p-4 feature-card">
                <div class="mb-3">
                    <i class="bi bi-chat-dots fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold">
                    Fitur Pesan
                </h5>
                <p class="text-muted small mb-0">
                    Berkomunikasi dengan admin maupun kurir secara langsung.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center p-4 feature-card">
                <div class="mb-3">
                    <i class="bi bi-shield-check fs-1 text-success"></i>
                </div>
                <h5 class="fw-bold">
                    Aman & Terpercaya
                </h5>
                <p class="text-muted small mb-0">
                    Seluruh transaksi tercatat sehingga lebih aman.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ================= PRODUK PILIHAN ================= --}}
<div class="container py-5" id="featured">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">
            <i class="bi bi-stars text-warning me-2"></i>
            Produk Pilihan
        </h3>
        <a href="{{ route('user.products') }}"
           class="btn btn-outline-success rounded-pill px-4">
            Lihat Semua
        </a>
    </div>

    @if($featuredProducts->count())
    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 border-0 shadow-sm">
                <img
                    src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                    class="card-img-top"
                    alt="{{ $product->nama_produk }}"
                    style="height:180px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h6 class="fw-semibold product-name mb-2">
                        {{ $product->nama_produk }}
                    </h6>
                    <div class="text-success fw-bold fs-5 mb-3">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="btn btn-success w-100">
                            <i class="bi bi-cart-plus me-2"></i>
                            Lihat Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-5 text-muted"></i>
            <p class="text-muted mt-3 mb-0">
                Belum ada produk yang tersedia.
            </p>
        </div>
    @endif
</div>

{{-- ================= PRODUK TERLARIS ================= --}}
@if(isset($produkTerlaris) && $produkTerlaris->count())
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-trophy-fill text-warning me-2"></i>
                Produk Terlaris
            </h3>
            <small class="text-muted">
                Produk yang paling banyak dibeli pelanggan
            </small>
        </div>
    </div>

    <div class="row g-4">
        @foreach($produkTerlaris as $product)
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 border-0 shadow-sm position-relative">
                {{-- Badge --}}
                <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-3 py-2">
                    <i class="bi bi-fire me-1"></i>
                    Terlaris
                </span>
                {{-- Gambar --}}
                <img
                    src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                    class="card-img-top"
                    alt="{{ $product->nama_produk }}"
                    style="height:180px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    {{-- Nama Produk --}}
                    <h6 class="fw-semibold product-name mb-2">
                        {{ $product->nama_produk }}
                    </h6>
                    {{-- Harga --}}
                    <div class="text-success fw-bold fs-5 mb-2">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </div>
                    {{-- Jumlah Terjual --}}
                    <small class="text-muted mb-3">
                        <i class="bi bi-bag-check me-1"></i>
                        Terjual {{ number_format($product->total_terjual) }} kali
                    </small>
                    <div class="mt-auto">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="btn btn-success w-100">
                            <i class="bi bi-cart-plus me-2"></i>
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
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            <i class="bi bi-heart-fill text-danger me-2"></i>
            Rekomendasi Untuk Anda
        </h3>
        <small class="text-muted">
            Dipilih berdasarkan riwayat pembelian Anda.
        </small>
    </div>

    <div class="row g-4">
        @foreach($recommendedProducts as $product)
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 border-0 shadow-sm position-relative">
                {{-- Badge --}}
                <span class="badge bg-primary position-absolute top-0 start-0 m-2 px-3 py-2">
                    <i class="bi bi-heart-fill me-1"></i>
                    Rekomendasi
                </span>

                {{-- Gambar --}}
                <img
                    src="{{ $product->image
                        ? asset('storage/'.$product->image)
                        : 'https://via.placeholder.com/300x200' }}"
                    class="card-img-top"
                    alt="{{ $product->nama_produk }}"
                    style="height:180px; object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    {{-- Nama Produk --}}
                    <h6 class="fw-semibold product-name mb-2">
                        {{ $product->nama_produk }}
                    </h6>
                    {{-- Harga --}}
                    <div class="text-success fw-bold fs-5 mb-3">
                        Rp {{ number_format($product->sizes->min('harga') ?? 0,0,',','.') }}
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('produk.detail', $product->id) }}"
                           class="btn btn-success w-100">
                            <i class="bi bi-cart-plus me-2"></i>
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
@endauth

{{-- ================= KATEGORI ================= --}}
<div class="container py-5">
    <div class="text-center mb-5">
        <h3 class="fw-bold mb-2">
            <i class="bi bi-grid-fill text-success me-2"></i>
            Kategori Produk
        </h3>
        <p class="text-muted mb-0">
            Temukan berbagai kategori bahan pokok sesuai kebutuhan Anda.
        </p>
    </div>

    <div class="row justify-content-center g-4">
        @foreach($categories as $kategori)
        <div class="col-6 col-md-3">
            <a href="{{ route('produk.index', ['kategori' => $kategori->id]) }}"
               class="text-decoration-none">
                <div class="card feature-card border-0 shadow-sm h-100 text-center">
                    <div class="card-body py-4">
                        <div class="mb-3">
                            <i class="bi bi-box-seam display-5 text-success"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">
                            {{ $kategori->nama_kategori }}
                        </h5>
                        <p class="text-muted small mb-3">

                            {{ $kategori->products_count }} Produk
                        </p>
                        <span class="btn btn-outline-success btn-sm">
                            Lihat Produk
                        </span>
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
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="fw-bold mb-2">
                    <i class="bi bi-receipt text-primary me-2"></i>
                    Pesanan Terakhir
                </h5>

                <p class="mb-1">
                    Nomor Pesanan
                    <strong>
                        #{{ $lastOrder->id }}
                    </strong>
                </p>

                <span class="badge bg-secondary px-3 py-2">
                    {{ ucfirst($lastOrder->status) }}
                </span>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="{{ route('pesanan.show', $lastOrder->id) }}"
                   class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

@endsection