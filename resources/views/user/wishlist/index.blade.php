@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container">
    <h2 class="mb-4">Wishlist Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($wishlistItems->isEmpty())
        <p>Wishlist kamu masih kosong.</p>
    @else
        <div class="row">
            @foreach ($wishlistItems as $item)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        @if($item->produk->image)
                            <img src="{{ asset('storage/' . $item->produk->image) }}" class="card-img-top" alt="{{ $item->produk->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->produk->nama_produk }}</h5>
                            <p class="card-text">Harga: Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            <p class="card-text">Stok: {{ ($item->produk->stok) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="mt-auto">
                                    <form action="{{ route('wishlist.destroy', $item->produk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari wishlist?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-block">Hapus dari Wishlist</button>
                                    </form>
                                </div>
                                <form action="{{ route('cart.store') }}" method="POST" class="mb-0">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->produk->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Tambah ke Keranjang">
                                        <i class="bi bi-plus-lg"></i>
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
@endsection
