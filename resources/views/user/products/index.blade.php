@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 fw-bold">Katalog Produk</h2>

    {{-- FILTER --}}
    <form id="filterForm" action="{{ route('produk.index') }}" method="GET" class="row mb-4 g-2">
        <div class="col-md-5">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama produk..."
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <input type="number"
                   name="min_price"
                   class="form-control"
                   placeholder="Harga Min"
                   value="{{ request('min_price') }}">
        </div>

        <div class="col-md-2">
            <input type="number"
                   name="max_price"
                   class="form-control"
                   placeholder="Harga Maks"
                   value="{{ request('max_price') }}">
        </div>
    </form>

    {{-- LIST PRODUK --}}
    <div class="row">
        @forelse($products as $product)
            @php
                $minPrice = $product->sizes->min('harga');
                $totalStock = $product->sizes->sum('stok');
            @endphp

            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">

                    {{-- GAMBAR --}}
                    <img src="{{ $product->image
                        ? asset('storage/' . $product->image)
                        : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                        class="card-img-top"
                        alt="{{ $product->nama_produk }}">

                    <div class="card-body d-flex flex-column">

                        {{-- NAMA --}}
                        <h5 class="fw-semibold mb-1">
                            {{ $product->nama_produk }}
                        </h5>

                        {{-- KATEGORI --}}
                        <p class="text-muted small mb-2">
                            {{ $product->kategori->nama_kategori ?? '-' }}
                        </p>

                        {{-- HARGA --}}
                        <p class="fw-bold text-success mb-1">
                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                        </p>

                        {{-- STOK --}}
                        <p class="small text-muted mb-2">
                            Stok tersedia: {{ $totalStock }}
                        </p>

                        {{-- UKURAN --}}
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($product->sizes as $size)
                                <span class="badge
                                    {{ $size->stok > 0 ? 'bg-light text-dark border' : 'bg-secondary' }}">
                                    {{ $size->size }}
                                </span>
                            @endforeach
                        </div>

                        {{-- AKSI --}}
                        <div class="mt-auto d-flex justify-content-between align-items-center">

                            <div class="d-flex gap-2">
                                {{-- DETAIL --}}
                                <a href="{{ route('produk.detail', $product->id) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Lihat Detail">
                                    <i class="bi bi-info-circle"></i>
                                </a>

                                {{-- WISHLIST --}}
                                <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Tambah Wishlist">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- PILIH UKURAN --}}
                            <a href="{{ route('produk.detail', $product->id) }}"
                               class="btn btn-sm btn-primary">
                                Pilih Ukuran
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Produk tidak ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

{{-- AUTO SUBMIT FILTER --}}
<script>
document.querySelectorAll('#filterForm input, #filterForm select')
    .forEach(el => el.addEventListener('change', () => {
        document.getElementById('filterForm').submit();
    }));
</script>

<style>
.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>
@endsection
