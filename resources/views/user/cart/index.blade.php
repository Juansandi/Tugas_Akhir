@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Keranjang Saya</h4>

    @php
        $subtotal = 0;
    @endphp

    @forelse ($cartItems as $item)
        @php
            $itemTotal = $item->produk->harga * $item->quantity;
            $subtotal += $itemTotal;
        @endphp

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap align-items-start">
                    <div class="d-flex align-items-start">
                        <img src="{{ asset('storage/' . $item->produk->image) }}" 
                            class="img-fluid rounded me-3" 
                            alt="{{ $item->produk->nama_produk }}" 
                            style="width: 120px; height: auto;">

                        <div>
                            <h5>{{ $item->produk->nama_produk }}</h5>

                            <div class="quantity-selector d-flex align-items-center my-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity('{{ $item->id }}', -1)">−</button>
                                <input type="number" 
                                    id="qty-{{ $item->id }}" 
                                    value="{{ $item->quantity }}" 
                                    class="form-control text-center mx-2" 
                                    style="width: 70px;" 
                                    readonly>
                                <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity('{{ $item->id }}', 1)">+</button>
                            </div>

                            <p class="mt-2">
                                Harga: Rp 
                                <span id="harga-{{ $item->id }}">
                                    {{ number_format($itemTotal, 0, ',', '.') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-2 mt-md-0">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p>Keranjang kosong.</p>
    @endforelse

    @if($cartItems->count())
    <div class="card p-3 shadow-sm mt-4">
        <h5>Total: Rp {{ number_format($subtotal, 0, ',', '.') }}</h5>
        <a href="{{ route('pesanan.checkoutForm') }}" class="btn btn-dark mt-3 w-100">Checkout Sekarang</a>
    </div>
    @endif
</div>

<script>
function updateQuantity(id, change) {
    const qtyInput = document.getElementById(`qty-${id}`);
    let qty = parseInt(qtyInput.value);
    qty = Math.max(1, qty + change);
    qtyInput.value = qty;

    // AJAX update
    fetch(`/cart/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`harga-${id}`).textContent = data.new_total;
            // Optionally update subtotal on client side here (or reload page)
            location.reload(); // simple way to refresh subtotal after update
        }
    })
    .catch(err => {
        alert('Gagal memperbarui keranjang.');
        console.error(err);
    });
}
</script>

<style>
.quantity-selector input::-webkit-inner-spin-button,
.quantity-selector input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
@endsection
