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

        <div class="card mb-3 shadow-sm {{ ($stokHabis || $stokKurang) ? 'border-warning' : '' }}">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap">

                    {{-- INFO ITEM --}}
                    <div class="d-flex align-items-start">

                        @if($item->type === 'paket')
                            {{-- ===================== --}}
                            {{-- ITEM PAKET --}}
                            {{-- ===================== --}}

                            @if($item->type === 'paket' && $item->paket)
                                <img src="{{ asset('storage/'.$item->paket->image) }}"
                                    class="img-fluid rounded me-3"
                                    style="width:120px; object-fit:cover;">
                            @endif


                            <div>
                                <h5 class="mb-1">{{ $item->paket->nama_paket }}</h5>
                                <p class="text-muted small mb-1">Paket Produk</p>

                                <p class="mb-1">
                                    Harga Paket:
                                    <strong>
                                        Rp {{ number_format($item->paket->harga_paket,0,',','.') }}
                                    </strong>
                                </p>

                                <p class="mb-1">
                                    Jumlah Paket: {{ $item->quantity }}
                                </p>
                                {{-- ISI PAKET --}}
                                <ul class="small text-muted mb-2">
                                    @if($item->paket)
                                        <ul class="small text-muted mb-2">
                                            @foreach($item->paket->detailPakets as $detail)
                                                <li>
                                                    {{ $detail->produk->nama_produk }}
                                                    @if($detail->size)
                                                        ({{ $detail->size->size }})
                                                    @endif
                                                    × {{ $detail->quantity * $item->quantity }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </ul>

                                <p class="mb-1">
                                    Subtotal:
                                    <strong>
                                        Rp {{ number_format(
                                            $item->paket->harga_paket * $item->quantity,
                                            0, ',', '.'
                                        ) }}
                                    </strong>
                                </p>
                            </div>

                        @else
                            {{-- ===================== --}}
                            {{-- PRODUK SATUAN --}}
                            {{-- ===================== --}}

                            <img src="{{ asset('storage/' . $item->produk->image) }}"
                                class="img-fluid rounded me-3"
                                style="width:120px; object-fit:cover;">

                            <div>
                                <h5 class="mb-1">{{ $item->produk->nama_produk }}</h5>

                                <p class="mb-1 text-muted">
                                    Ukuran: <strong>{{ $item->size->size }}</strong>
                                </p>

                                <p class="mb-1">
                                    Harga:
                                    Rp {{ number_format($item->size->harga, 0, ',', '.') }}
                                </p>

                                {{-- QTY --}}
                                <div class="d-flex align-items-center my-2">
                                    <button class="btn btn-outline-secondary btn-sm btn-minus"
                                        data-id="{{ $item->id }}"
                                        data-stock="{{ $item->size->stok }}">
                                        −
                                    </button>

                                    <input type="number"
                                        id="qty-{{ $item->id }}"
                                        value="{{ $item->quantity }}"
                                        class="form-control text-center mx-2"
                                        style="width:70px;"
                                        readonly>

                                    <button class="btn btn-outline-secondary btn-sm btn-plus"
                                        data-id="{{ $item->id }}"
                                        data-stock="{{ $item->size->stok }}">
                                        +
                                    </button>
                                </div>

                                <p class="mb-1">
                                    Subtotal:
                                    <strong>
                                        Rp {{ number_format(
                                            $item->size->harga * $item->quantity,
                                            0, ',', '.'
                                        ) }}
                                    </strong>
                                </p>
                            </div>
                        @endif
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
                    Lanjut ke Pembayaran
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
