@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Our Collection Of Products</h2>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-12 col-sm-6 col-md-4 mb-4">
            <div class="card h-100">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="No image">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="text-muted">{{ $product->kategori->nama_kategori ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
