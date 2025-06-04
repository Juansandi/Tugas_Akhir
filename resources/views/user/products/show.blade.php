@extends('layouts.app')

@section('content')
<div class="container-md">

    <div class="row bg-light p-4 rounded shadow-sm">
        <!-- Product Image -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="product-image shadow-sm rounded overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->nama_produk }}">
                @else
                    <img src="https://via.placeholder.com/500x400?text=No+Image" class="img-fluid" alt="No Image">
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6 col-md-12">
            <div class="product-info p-4 shadow rounded bg-white">
                <h2 class="product-title mb-2">{{ $product->nama_produk }}</h2>
                
                <div class="rating mb-3">
                    <span class="stars">★★★★★</span>
                    <span class="review-count text-muted">({{ rand(1, 50) }} reviews)</span>
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
                    
                    <ul class="product-features">
                        <li>Produk berkualitas tinggi</li>
                        <li>Tersedia dengan berbagai pilihan</li>
                        <li>Garansi kepuasan pelanggan</li>
                    </ul>
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
                    <form action="{{ route('wishlist.store', $product->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-lg" title="Tambah ke Wishlist" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; padding: 0;">
                            <i class="bi bi-heart"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                </li>
            </ul>
            <div class="tab-content mt-4" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    @if($product->deskripsi)
                        <p>{{ $product->deskripsi }}</p>
                    @else
                        <p>Belum ada deskripsi lengkap untuk produk ini.</p>
                    @endif
                    
                    @if($product->kategori)
                        <p><strong>Kategori:</strong> {{ $product->kategori->nama_kategori }}</p>
                    @endif
                    
                    <ul>
                        <li>Produk berkualitas tinggi</li>
                        <li>Tersedia dengan berbagai pilihan</li>
                        <li>Garansi kepuasan pelanggan</li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <p>Belum ada review untuk produk ini.</p>
                    <p>Jadilah yang pertama memberikan review untuk <strong>{{ $product->nama_produk }}</strong>.</p>
                </div>
            </div>
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

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    background: none;
    border-bottom: 2px solid #007bff;
    color: #007bff;
}
</style>
@endsection
