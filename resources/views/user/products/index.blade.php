@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Our Collection Of Products</h2>

    {{-- Search Bar dan Filter --}}
    <form id="filterForm" action="{{ route('produk.index') }}" method="GET" class="row mb-4 g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari nama produk..." value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="kategori" id="kategoriSelect" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $kategori)
                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" name="min_price" id="minPriceInput" class="form-control" placeholder="Harga Min" value="{{ request('min_price') }}">
        </div>

        <div class="col-md-2">
            <input type="number" name="max_price" id="maxPriceInput" class="form-control" placeholder="Harga Max" value="{{ request('max_price') }}">
        </div>
    </form>

    <div class="row">
        @forelse($products as $product)
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="No image">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->nama_produk }}</h5>
                    <p class="card-text mb-1">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="text-muted small mb-3">{{ $product->kategori->nama_kategori ?? '-' }}</p>
                    <p class="text-muted small mb-3">Stok : {{ $product->stok }}</p>

                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <a href="{{ route('produk.detail', $product->id) }}" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                <i class="bi bi-info-circle"></i>
                            </a>

                            <form action="{{ route('wishlist.store', $product->id) }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Tambah ke Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </form>
                        </div>

                        <form action="{{ route('cart.store') }}" method="POST" class="mb-0">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Tambah ke Keranjang">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p>Produk tidak ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

{{-- Script untuk auto submit form saat input/filter berubah --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        const kategoriSelect = document.getElementById('kategoriSelect');
        const minPriceInput = document.getElementById('minPriceInput');
        const maxPriceInput = document.getElementById('maxPriceInput');

        let typingTimer;
        const typingInterval = 500; 
        searchInput.addEventListener('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => filterForm.submit(), typingInterval);
        });

        searchInput.addEventListener('keydown', function () {
            clearTimeout(typingTimer);
        });

        // Auto submit saat filter select/category berubah
        kategoriSelect.addEventListener('change', function () {
            filterForm.submit();
        });

        // Auto submit saat harga min/max berubah (on input)
        minPriceInput.addEventListener('change', function () {
            filterForm.submit();
        });

        maxPriceInput.addEventListener('change', function () {
            filterForm.submit();
        });
    });
</script>
@endsection
