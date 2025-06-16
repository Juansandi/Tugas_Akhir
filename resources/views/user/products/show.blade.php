@extends('layouts.app')

@section('content')
<div class="container-md">

    <div class="row bg-light p-4 rounded shadow-sm d-flex align-items-stretch">
        <!-- Product Image -->
        <div class="col-lg-6 col-md-12 mb-4 d-flex flex-column">
            <div class="product-image shadow-sm rounded overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->nama_produk }}">
                @else
                    <img src="https://via.placeholder.com/500x400?text=No+Image" class="img-fluid" alt="No Image">
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6 col-md-12 d-flex flex-column">
            <div class="product-info p-4 shadow rounded bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="product-title mb-0">{{ $product->nama_produk }}</h2>

                    <form action="{{ route('wishlist.store', $product->id) }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" title="Tambah ke Wishlist" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-heart"></i>
                        </button>
                    </form>
                </div>

                <div class="rating mb-3">
                    <span class="stars">★★★★★</span>
                    <span class="review-count text-muted">({{ $product->reviews->count() }} ulasan)</span>
                </div>

                @if($product->kategori)
                <p class="text-muted mb-2">Kategori: {{ $product->kategori->nama_kategori }}</p>
                @endif

                <h3 class="price text-success mb-4">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>

                <div class="description mb-4">
                    @if($product->deskripsi)
                        <p>{{ $product->deskripsi }}</p>
                    @else
                        <p>Produk berkualitas tinggi yang tersedia di toko kami. Dapatkan pengalaman berbelanja terbaik dengan produk pilihan.</p>
                    @endif
                </div>

                <!-- Quantity and Actions -->
                <div class="product-actions">
                    <form action="{{ route('cart.store') }}" method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">

                        <div class="quantity-selector d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="decreaseQty()">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control text-center" style="width: 80px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="increaseQty()">+</button>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100 mb-2">Add to Cart</button>
                        <button type="button" class="btn btn-outline-dark btn-lg w-100">Buy Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
    <div class="col-12">
        <h4>Ulasan Pengguna</h4>
        @forelse($product->reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review->user->username ?? 'Anonim' }}</strong>
                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="text-warning">
                        {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                    </div>
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
        @endforelse
    </div>
</div>

</div>

<script>
function increaseQty() {
    const qtyInput = document.getElementById('quantity');
    qtyInput.value = parseInt(qtyInput.value) + 1;
}

function decreaseQty() {
    const qtyInput = document.getElementById('quantity');
    if (parseInt(qtyInput.value) > 1) {
        qtyInput.value = parseInt(qtyInput.value) - 1;
    }
}
</script>

<style>
.product-title {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
}
.stars {
    color: #ffc107;
    font-size: 1.2rem;
}
.price {
    font-size: 1.8rem;
    font-weight: 700;
}
.product-features {
    list-style: none;
    padding-left: 0;
}
.product-features li {
    padding: 0.25rem 0;
    position: relative;
    padding-left: 1.5rem;
}
.product-features li:before {
    content: "•";
    color: #28a745;
    font-weight: bold;
    position: absolute;
    left: 0;
}
.quantity-selector {
    max-width: 200px;
}
.product-image img {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
}
.product-image, .product-info {
    min-height: 450px; /* agar tinggi seimbang */
    height: 100%;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
@endsection
