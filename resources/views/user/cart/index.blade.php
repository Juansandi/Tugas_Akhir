@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-cart3 text-success me-2"></i>
                Keranjang Belanja
            </h2>

            <p class="text-muted mb-0">
                {{ $cartItems->count() }} item di dalam keranjang
            </p>
        </div>

    </div>

    {{-- ALERT ERROR STOK --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($stokError) && $stokError)
        <div class="alert alert-warning">
            Beberapa produk di keranjang sudah tidak tersedia atau stok berubah.
            Silakan sesuaikan jumlah sebelum melanjutkan pembayaran.
        </div>
    @endif

    @php
        $subtotal = 0;
    @endphp

    {{-- LIST CART --}}
    @forelse ($cartItems as $item)
        @php
            if ($item->type === 'produk') {
                $itemTotal = $item->size->harga * $item->quantity;
                $stokHabis = $item->size->stok <= 0;
                $stokKurang = $item->quantity > $item->size->stok;
            } else {
                $itemTotal = $item->paket->harga_paket * $item->quantity;
                $stokHabis = false;
                $stokKurang = false;

                foreach ($item->paket->detailPakets as $detail) {
                    if ($detail->size && $detail->size->stok < ($detail->quantity * $item->quantity)) {
                        $stokKurang = true;
                    }
                }
            }

            $subtotal += $itemTotal;
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-4 {{ ($stokHabis || $stokKurang) ? 'border-warning' : '' }}">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start flex-wrap">

                    {{-- ================= INFO ITEM ================= --}}
<div class="d-flex align-items-start flex-grow-1">

    {{-- ================= PAKET ================= --}}
    @if($item->type === 'paket')

        <img src="{{ asset('storage/'.$item->paket->image) }}"
            class="img-fluid rounded-3 shadow-sm me-4"
            style="width:140px;height:140px;object-fit:cover;">

        <div class="flex-grow-1">

            <span class="badge bg-success mb-2">
                <i class="bi bi-box-seam me-1"></i>
                Paket Hemat
            </span>

            <h4 class="fw-bold mb-2">
                {{ $item->paket->nama_paket }}
            </h4>

            <div class="mb-2">

                <small class="text-muted">
                    Harga Paket
                </small>

                <h5 class="fw-bold text-success mb-0">
                    Rp {{ number_format($item->paket->harga_paket,0,',','.') }}
                </h5>

            </div>

            <div class="mb-3">

                <span class="badge bg-light text-dark border">
                    Jumlah Paket :
                    {{ $item->quantity }}
                </span>

            </div>

            {{-- ISI PAKET --}}
            <div class="border rounded-3 bg-light p-3 mb-3">

                <div class="fw-semibold mb-2">
                    Isi Paket
                </div>

                @foreach($item->paket->detailPakets as $detail)

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <div>

                            <i class="bi bi-check-circle-fill text-success me-2"></i>

                            {{ $detail->produk->nama_produk }}

                            @if($detail->size)
                                <small class="text-muted">
                                    ({{ $detail->size->size }})
                                </small>
                            @endif

                        </div>

                        <strong>

                            × {{ $detail->quantity * $item->quantity }}

                        </strong>

                    </div>

                @endforeach

            </div>

            <h5 class="fw-bold text-success">

                Subtotal :
                Rp {{ number_format(
                    $item->paket->harga_paket * $item->quantity,
                    0,
                    ',',
                    '.'
                ) }}

            </h5>

        </div>

    @else

    {{-- ================= PRODUK ================= --}}

        <img src="{{ asset('storage/'.$item->produk->image) }}"
            class="img-fluid rounded-3 shadow-sm me-4"
            style="width:140px;height:140px;object-fit:cover;">

        <div class="flex-grow-1">

            <h4 class="fw-bold mb-2">
                {{ $item->produk->nama_produk }}
            </h4>

            <div class="mb-3">

                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-box me-1"></i>

                    {{ $item->size->size }}

                </span>

            </div>

            <small class="text-muted">
                Harga
            </small>

            <h5 class="fw-bold text-success mb-3">

                Rp {{ number_format($item->size->harga,0,',','.') }}

            </h5>

            {{-- QUANTITY --}}
            <div class="d-inline-flex align-items-center border rounded-pill overflow-hidden mb-3">

                <button
                    class="btn btn-light btn-minus"
                    data-id="{{ $item->id }}"
                    data-stock="{{ $item->size->stok }}">

                    <i class="bi bi-dash"></i>

                </button>

                <input
                    type="number"
                    id="qty-{{ $item->id }}"
                    value="{{ $item->quantity }}"
                    readonly
                    class="border-0 text-center"
                    style="width:65px;">

                <button
                    class="btn btn-light btn-plus"
                    data-id="{{ $item->id }}"
                    data-stock="{{ $item->size->stok }}">

                    <i class="bi bi-plus"></i>

                </button>

            </div>

            <h5 class="fw-bold text-success">

                Subtotal :
                Rp {{ number_format(
                    $item->size->harga * $item->quantity,
                    0,
                    ',',
                    '.'
                ) }}

            </h5>

        </div>

    @endif

</div>

                    <div class="ms-3 d-flex align-items-start">

    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn btn-outline-danger rounded-pill px-3"
            onclick="return confirm('Hapus item dari keranjang?')">

            <i class="bi bi-trash3 me-1"></i>
            Hapus

        </button>

    </form>

</div>

                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Keranjang kosong.</p>
    @endforelse

    {{-- ================= RINGKASAN BELANJA ================= --}}
@if($cartItems->count())

<div class="card border-0 shadow-sm rounded-4 mt-4">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-4">
            <i class="bi bi-receipt-cutoff text-success me-2"></i>
            Ringkasan Belanja
        </h4>

        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">
                Total Item
            </span>

            <strong>
                {{ $cartItems->count() }}
            </strong>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">
                Subtotal
            </span>

            <strong>
                Rp {{ number_format($subtotal,0,',','.') }}
            </strong>
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h5 class="mb-0">
                Total Pembayaran
            </h5>

            <h3 class="fw-bold text-success mb-0">
                Rp {{ number_format($subtotal,0,',','.') }}
            </h3>

        </div>

        @if(isset($stokError) && $stokError)

            <button
                class="btn btn-secondary btn-lg w-100 rounded-pill"
                disabled>

                <i class="bi bi-exclamation-triangle me-2"></i>

                Stok Tidak Mencukupi

            </button>

        @else

            <a
                href="{{ route('pesanan.checkoutForm') }}"
                class="btn btn-success btn-lg w-100 rounded-pill">

                <i class="bi bi-credit-card me-2"></i>

                Lanjut ke Pembayaran

            </a>

        @endif

    </div>

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
