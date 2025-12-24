@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="mb-3">Keranjang Saya</h4>

    {{-- ALERT ERROR STOK --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($stokError) && $stokError)
        <div class="alert alert-warning">
            Beberapa produk di keranjang sudah tidak tersedia atau stok berubah.
            Silakan sesuaikan jumlah sebelum checkout.
        </div>
    @endif

    @php
        $subtotal = 0;
    @endphp

    {{-- LIST CART --}}
    @forelse ($cartItems as $item)
        @php
            $itemTotal = $item->size->harga * $item->quantity;
            $subtotal += $itemTotal;
            $stokHabis = $item->size->stok <= 0;
            $stokKurang = $item->quantity > $item->size->stok;
        @endphp

        <div class="card mb-3 shadow-sm {{ ($stokHabis || $stokKurang) ? 'border-warning' : '' }}">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap">

                    {{-- INFO PRODUK --}}
                    <div class="d-flex align-items-start">
                        <img src="{{ asset('storage/' . $item->produk->image) }}"
                             class="img-fluid rounded me-3"
                             style="width:120px; object-fit:cover;">

                        <div>
                            <h5 class="mb-1">{{ $item->produk->nama_produk }}</h5>

                            <p class="mb-1 text-muted">
                                Ukuran: <strong>{{ $item->size->size }}</strong>
                            </p>

                            <p class="mb-1">
                                Harga: Rp {{ number_format($item->size->harga, 0, ',', '.') }}
                            </p>

                            {{-- QTY --}}
                            <div class="d-flex align-items-center my-2">
                                <button class="btn btn-outline-secondary btn-sm btn-minus"
                                    data-id="{{ $item->id }}"
                                    data-stock="{{ $item->size->stok }}"
                                    {{ $stokHabis ? 'disabled' : '' }}>
                                    −
                                </button>

                                <input type="number"
                                       id="qty-{{ $item->id }}"
                                       value="{{ min($item->quantity, $item->size->stok) }}"
                                       class="form-control text-center mx-2"
                                       style="width:70px;"
                                       readonly>

                                <button class="btn btn-outline-secondary btn-sm btn-plus"
                                    data-id="{{ $item->id }}"
                                    data-stock="{{ $item->size->stok }}"
                                    {{ $stokHabis ? 'disabled' : '' }}>
                                    +
                                </button>
                            </div>

                            {{-- SUBTOTAL --}}
                            <p class="mb-1">
                                Subtotal:
                                <strong>
                                    Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                </strong>
                            </p>

                            {{-- STATUS STOK --}}
                            @if($stokHabis)
                                <small class="text-danger fw-semibold">
                                    Stok habis
                                </small>
                            @elseif($stokKurang)
                                <small class="text-warning fw-semibold">
                                    Stok tersisa {{ $item->size->stok }}, silakan kurangi jumlah
                                </small>
                            @else
                                <small class="text-muted">
                                    Stok tersedia: {{ $item->size->stok }}
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- HAPUS --}}
                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Keranjang kosong.</p>
    @endforelse

    {{-- TOTAL & CHECKOUT --}}
    @if($cartItems->count())
        <div class="card p-3 shadow-sm mt-4">
            <h5>Total: Rp {{ number_format($subtotal, 0, ',', '.') }}</h5>

            @if(isset($stokError) && $stokError)
                <button class="btn btn-secondary mt-3 w-100" disabled>
                    Stok Tidak Mencukupi
                </button>
            @else
                <a href="{{ route('pesanan.checkoutForm') }}"
                   class="btn btn-dark mt-3 w-100">
                    Checkout Sekarang
                </a>
            @endif
        </div>
    @endif
</div>

{{-- SCRIPT UPDATE QTY --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            changeQty(btn.dataset.id, 1, btn.dataset.stock);
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            changeQty(btn.dataset.id, -1, btn.dataset.stock);
        });
    });

});

function changeQty(id, change, maxStock) {
    const qtyInput = document.getElementById(`qty-${id}`);
    let qty = parseInt(qtyInput.value);

    qty += change;
    if (qty < 1) qty = 1;

    if (qty > maxStock) {
        alert('Jumlah melebihi stok tersedia');
        return;
    }

    fetch("{{ route('cart.update', ':id') }}".replace(':id', id), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: qty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            qtyInput.value = data.quantity;
            location.reload(); // refresh subtotal & status stok
        } else {
            alert(data.message || 'Gagal update jumlah');
        }
    })
    .catch(() => alert('Gagal update jumlah'));
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
