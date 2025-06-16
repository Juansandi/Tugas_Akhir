@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Wishlist Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($wishlistItems->isEmpty())
        <div class="text-center">
            <p class="text-muted">Wishlist kamu masih kosong.</p>
        </div>
    @else
        <div class="row">
            @foreach ($wishlistItems as $item)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        {{-- Gambar Produk --}}
                        @if($item->produk->image)
                            <img src="{{ asset('storage/' . $item->produk->image) }}" class="card-img-top img-fluid" alt="{{ $item->produk->nama_produk }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                        @endif

                        {{-- Konten Produk --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="{{ $item->produk->nama_produk }}">{{ $item->produk->nama_produk }}</h5>
                            <p class="card-text mb-1 text-success">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            <p class="card-text text-muted small">Stok: {{ $item->produk->stok }}</p>

                            {{-- Tombol Aksi --}}
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <form action="{{ route('wishlist.destroy', $item->produk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari wishlist?')" class="me-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus dari Wishlist">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                                <form action="{{ route('cart.store') }}" method="POST" class="mb-0">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->produk->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Tambah ke Keranjang">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Tambahan Style --}}
<style>
    .card-img-top {
        height: 180px;
        object-fit: cover;
        border-bottom: 1px solid #dee2e6;
    }
    .card-title {
        font-size: 1rem;
        font-weight: 600;
    }
</style>
@endsection
