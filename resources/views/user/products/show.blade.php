@extends('layouts.app')

@section('content')
<div class="container-md py-4">
    <div class="row bg-light p-4 rounded shadow-sm">
        {{-- IMAGE --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <img
                        src="{{ asset('storage/'.$product->image) }}"
                        class="img-fluid rounded"
                        style="
                            max-height:420px;
                            width:100%;
                            object-fit:cover;
                        ">
                </div>
            </div>
        </div>

        {{-- INFO --}}
        <div class="col-lg-6">
            <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                {{-- Nama Produk --}}
                <h1 class="fw-bold mb-2">
                    {{ $product->nama_produk }}
                </h1>

                {{-- Rating --}}
                @if($totalReviews > 0)
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning fs-5">
                            @for($i=1;$i<=5;$i++)
                                <i class="bi {{ $i <= floor($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>

                        <span class="ms-3 fw-semibold">
                            {{ number_format($avgRating,1) }}/5
                        </span>

                        <small class="text-muted ms-2">

                            ({{ $totalReviews }} ulasan)
                        </small>
                    </div>
                @else
                    <p class="text-muted mb-3">

                        Belum ada ulasan
                    </p>
                @endif

                {{-- Badge kategori --}}
                <div class="mb-3">
                    <span class="badge bg-success-subtle text-success border px-3 py-2">
                        <i class="bi bi-tag-fill me-1"></i>
                        {{ $product->kategori->nama_kategori ?? '-' }}
                    </span>
                </div>

                @php
                    $minPrice = $product->sizes->min('harga');
                @endphp

                {{-- Harga --}}
                <div class="mb-4">
                    <small class="text-muted">
                        Mulai dari
                    </small>

                    <h1
                        id="priceDisplay"
                        class="fw-bold text-success display-6 mb-0">
                        Rp {{ number_format($minPrice,0,',','.') }}
                    </h1>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <p class="text-secondary mb-0">
                        {{ $product->deskripsi }}
                    </p>
                </div>

                {{-- Informasi Singkat --}}
                <div class="border rounded-3 p-3 bg-light mb-4">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Produk Berkualitas
                        </div>

                        <div class="col-6 mb-2">
                            <i class="bi bi-truck text-success me-2"></i>
                            Kurir Internal
                        </div>

                        <div class="col-6">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            Transaksi Aman
                        </div>
                        <div class="col-6">
                            <i class="bi bi-clock-history text-success me-2"></i>
                            Pengiriman Cepat
                        </div>
                    </div>
                </div>  

                {{-- ================= FORM CART ================= --}}
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="produk_id"
                        value="{{ $product->id }}">

                    {{-- Ukuran --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Pilih Ukuran
                        </label>

                        <select
                            name="size_id"
                            id="sizeSelect"
                            class="form-select form-select-lg"
                            required
                            onchange="updateUI()">

                            <option value="">
                                -- Pilih ukuran --
                            </option>

                            @foreach($product->sizes as $size)
                                <option
                                    value="{{ $size->id }}"
                                    data-price="{{ $size->harga }}"
                                    data-stock="{{ $size->stok }}"
                                    {{ $size->stok==0 ? 'disabled':'' }}>

                                    {{ $size->size }}
                                    •
                                    Rp {{ number_format($size->harga,0,',','.') }}
                                    {{ $size->stok==0 ? '(Habis)' : '' }}
                                </option>
                            @endforeach
                        </select>

                        <div
                            id="stockInfo"
                            class="mt-2 small fw-semibold text-success">
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            Jumlah
                        </label>

                        <div class="input-group quantity-group">
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="decreaseQty()">
                                <i class="bi bi-dash-lg"></i>
                            </button>

                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                value="1"
                                min="1"
                                class="form-control text-center fw-bold"
                                disabled>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="increaseQty()">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-grid">
                        <button
                            id="btnAdd"
                            class="btn btn-success btn-lg py-3"
                            disabled>
                            <i class="bi bi-cart-plus me-2"></i>
                            Tambah ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= REVIEW ================= --}}
    <div class="row mt-5">
        <div class="col-lg-10 mx-auto">
            <h4 class="fw-bold mb-4">
                ⭐ Ulasan Pengguna
            </h4>

            @forelse($limitedReviews as $review)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex">
                        {{-- Avatar --}}
                        <div
                            class="rounded-circle bg-success text-white
                                d-flex align-items-center justify-content-center
                                fw-bold me-3"
                            style="width:55px;height:55px;">
                            {{ strtoupper(substr($review->user->username ?? 'U',0,1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        {{ $review->user->username ?? 'Anonim' }}
                                    </h6>
                                    <div class="text-warning mb-2">
                                        @for($i=1;$i<=5;$i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $review->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <p class="mb-0">
                                {{ $review->comment }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @empty

            <div class="text-center py-5">
                <i class="bi bi-chat-square-text fs-1 text-secondary"></i>
                <h5 class="mt-3">
                    Belum ada ulasan
                </h5>
                <p class="text-muted">
                    Jadilah pelanggan pertama yang memberikan ulasan.
                </p>
            </div>
            @endforelse

            @if($totalReviews>3)
            <div class="text-center mt-4">
                <a
                    href="{{ route('user.products.reviews',$product->id) }}"
                    class="btn btn-outline-success rounded-pill px-4">
                    Lihat Semua Ulasan ({{ $totalReviews }})
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
function updateUI() {
    const select = document.getElementById('sizeSelect');
    const option = select.options[select.selectedIndex];

    if (!option.value) return;

    const price = parseInt(option.dataset.price);
    const stock = parseInt(option.dataset.stock);

    document.getElementById('priceDisplay').innerText =
        'Rp ' + price.toLocaleString('id-ID');

    const qty = document.getElementById('quantity');
    const btn = document.getElementById('btnAdd');

    if (stock > 0) {
        qty.disabled = false;
        qty.max = stock;
        qty.value = 1;
        btn.disabled = false;
        document.getElementById('stockInfo').innerHTML =
        '<i class="bi bi-check-circle-fill me-1"></i>Stok tersedia : <b>'+stock+'</b>';
    } else {
        qty.disabled = true;
        btn.disabled = true;
        document.getElementById('stockInfo').innerHTML =
        '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Stok habis</span>';
    }
}
function increaseQty(){
    const qty=document.getElementById('quantity');
    if(qty.disabled) return;
    if(qty.max && Number(qty.value)<Number(qty.max))
        qty.value++;
}

function decreaseQty(){
    const qty=document.getElementById('quantity');
    if(qty.disabled) return;
    if(Number(qty.value)>1)
        qty.value--;
}
</script>
<style>
.product-image{
    max-width:100%;
    max-height:100%;
    object-fit:contain;
    border-radius:12px;
}
.product-image:hover{
    transform:scale(1.02);
}
.bg-success-subtle{
    background:#e8f5ee;
}
.quantity-group button{
    width:55px;
}
.quantity-group input{
    font-size:1.1rem;
}
#btnAdd{
    transition:.25s;
}
#btnAdd:not(:disabled):hover{
    transform:translateY(-2px);
    box-shadow:0 .7rem 1.5rem rgba(25,135,84,.25);
}
</style>
@endsection