@extends('layouts.app')

@section('content')
<div class="container-md py-4">

    <div class="row bg-light p-4 rounded shadow-sm">

        {{-- IMAGE --}}
        <div class="col-lg-6 mb-4">
            <div class="rounded overflow-hidden shadow-sm">
                <img src="{{ $product->image
                    ? asset('storage/' . $product->image)
                    : 'https://via.placeholder.com/500x400?text=No+Image' }}"
                    class="img-fluid w-100"
                    style="max-height:450px; object-fit:cover;">
            </div>
        </div>

        {{-- INFO --}}
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded shadow-sm h-100">

                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h2 class="fw-bold">{{ $product->nama_produk }}</h2>

                    <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-heart"></i>
                        </button>
                    </form>
                </div>

                <p class="text-muted mb-2">
                    Kategori: {{ $product->kategori->nama_kategori ?? '-' }}
                </p>

                @php
                    $minPrice = $product->sizes->min('harga');
                @endphp

                <h3 class="text-success fw-bold mb-3" id="priceDisplay">
                    Rp {{ number_format($minPrice, 0, ',', '.') }}
                </h3>

                <p class="mb-4">{{ $product->deskripsi }}</p>

                {{-- FORM CART --}}
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="produk_id" value="{{ $product->id }}">

                    {{-- UKURAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Ukuran</label>
                        <select name="size_id"
                                id="sizeSelect"
                                class="form-select"
                                required
                                onchange="updateUI()">

                            <option value="">-- Pilih ukuran --</option>

                            @foreach($product->sizes as $size)
                                <option value="{{ $size->id }}"
                                        data-price="{{ $size->harga }}"
                                        data-stock="{{ $size->stok }}"
                                        {{ $size->stok == 0 ? 'disabled' : '' }}>
                                    {{ $size->size }}
                                    — Rp {{ number_format($size->harga, 0, ',', '.') }}
                                    {{ $size->stok == 0 ? '(Habis)' : '(stok '.$size->stok.')' }}
                                </option>
                            @endforeach
                        </select>

                        <small id="stockInfo" class="text-muted"></small>
                    </div>

                    {{-- QTY --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Jumlah</label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               value="1"
                               min="1"
                               class="form-control"
                               disabled>
                    </div>

                    {{-- ACTION --}}
                    <button type="submit"
                            id="btnAdd"
                            class="btn btn-dark btn-lg w-100"
                            disabled>
                        Tambah ke Keranjang
                    </button>
                </form>

            </div>
        </div>
    </div>

    {{-- REVIEWS --}}
    <div class="row mt-5">
        <div class="col-12">
            <h4>Ulasan Pengguna</h4>

            @forelse($product->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <strong>{{ $review->user->username ?? 'Anonim' }}</strong>
                        <div class="text-warning">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada ulasan.</p>
            @endforelse
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
        document.getElementById('stockInfo').innerText =
            'Stok tersedia: ' + stock;
    } else {
        qty.disabled = true;
        btn.disabled = true;
        document.getElementById('stockInfo').innerText = 'Stok habis';
    }
}
</script>
@endsection
