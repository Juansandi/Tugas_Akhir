@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                Katalog Produk
            </h2>
            <p class="text-muted mb-0">
                Temukan berbagai kebutuhan bahan pokok dengan mudah.
            </p>
        </div>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form id="filterForm"
                  action="{{ route('produk.index') }}"
                  method="GET">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari nama produk..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select
                            name="kategori"
                            class="form-select">
                            <option value="">
                                Semua Kategori
                            </option>
                            @foreach($categories as $kategori)
                                <option
                                    value="{{ $kategori->id }}"
                                    {{ request('kategori') == $kategori->id ? 'selected' : '' }}>

                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <input
                            type="number"
                            name="min_price"
                            class="form-control"
                            placeholder="Harga Min"
                            value="{{ request('min_price') }}">
                    </div>
                    <div class="col-lg-2">
                        <input
                            type="number"
                            name="max_price"
                            class="form-control"
                            placeholder="Harga Maks"
                            value="{{ request('max_price') }}">
                    </div>
                    <div class="col-lg-1 d-grid">
                        <a href="{{ route('produk.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= LIST PRODUK ================= --}}
    <div class="row g-4">
        @forelse($products as $product)
            @php
                $minPrice = $product->sizes->min('harga');
                $totalStock = $product->sizes->sum('stok');
            @endphp

            <div class="col-sm-6 col-lg-4">
                <div class="card product-card h-100 border-0 shadow-sm"
                    onclick="window.location='{{ route('produk.detail',$product->id) }}'"
                    style="cursor:pointer;">
                    {{-- IMAGE --}}
                    <img
                        src="{{ $product->image
                            ? asset('storage/'.$product->image)
                            : 'https://via.placeholder.com/500x350?text=No+Image' }}"
                        class="card-img-top"
                        alt="{{ $product->nama_produk }}">
                    <div class="card-body d-flex flex-column">
                        {{-- Nama --}}
                        <h5 class="fw-bold mb-2">
                            {{ $product->nama_produk }}
                        </h5>

                        {{-- Kategori --}}
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-tag-fill text-success me-1"></i>
                                {{ $product->kategori->nama_kategori ?? '-' }}
                            </span>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Mulai dari
                            </small>
                            <span class="fs-4 fw-bold text-success">
                                Rp {{ number_format($minPrice,0,',','.') }}
                            </span>
                        </div>

                        {{-- Stok --}}
                        <div class="mb-3">
                            @if($totalStock > 0)
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    {{ $totalStock }} tersedia
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Stok Habis
                                </span>
                            @endif
                        </div>

                        {{-- Ukuran --}}
                        <div class="mb-4">
                            @foreach($product->sizes as $size)
                                <span class="badge
                                    {{ $size->stok > 0
                                        ? 'bg-light text-dark border'
                                        : 'bg-secondary' }}
                                    me-1 mb-1">
                                    {{ $size->size }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-auto">
                            <a href="{{ route('produk.detail',$product->id) }}"
                            class="btn btn-success w-100"
                            onclick="event.stopPropagation();">
                                <i class="bi bi-cart-plus me-2"></i>
                                Pilih Ukuran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center py-5">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="mt-3">
                        Produk tidak ditemukan
                    </h5>
                    <p class="text-muted mb-0">
                        Coba ubah kata kunci pencarian atau filter kategori.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
    {{-- ================= PAGINATION ================= --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

{{-- ================= AUTO FILTER ================= --}}
<script>
document.querySelectorAll('#filterForm input,#filterForm select')
.forEach(el=>{
    el.addEventListener('change',()=>{
        document.getElementById('filterForm').submit();
    });
});
</script>
<style>
.card-img-top{
    height:220px;
    object-fit:cover;
}
.product-card{
    transition:.25s ease;
    border-radius:14px;
    overflow:hidden;
}
.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 .9rem 1.6rem rgba(0,0,0,.12)!important;
}
.product-card .btn{
    border-radius:8px;
}
.badge{
    font-size:.8rem;
}
.card-body{
    padding:1.25rem;
}
</style>
@endsection