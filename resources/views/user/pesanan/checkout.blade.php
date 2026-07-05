@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-credit-card text-success me-2"></i>
                Proses Pembayaran
            </h2>
            <p class="text-muted mb-0">
                Periksa kembali pesanan Anda sebelum melakukan pembayaran.
            </p>
        </div>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf
        <h4 class="fw-bold mb-3">
            <i class="bi bi-geo-alt-fill text-success me-2"></i>
            Alamat Pengiriman
        </h4>

        @forelse ($alamatList as $alamat)
        <div class="card border-0 shadow-sm rounded-4 mb-3">
            <div class="card-body">
                <div class="form-check">
                    <input
                        class="form-check-input mt-1"
                        type="radio"
                        name="alamat_id"
                        value="{{ $alamat->id }}"
                        {{ $alamat->is_default ? 'checked' : '' }}>

                    <label class="form-check-label w-100">
                        <div class="d-flex justify-content-between">
                            <strong>
                                <i class="bi bi-house-door text-success me-1"></i>
                                {{ $alamat->label }}
                            </strong>

                            @if($alamat->is_default)
                                <span class="badge bg-success">
                                    Utama
                                </span>
                            @endif
                        </div>

                        <div class="text-muted mt-2">
                            {{ $alamat->alamat }}
                        </div>
                    </label>
                </div>
            </div>
        </div>

        @empty
        <div class="alert alert-warning">
            Belum ada alamat pengiriman.
        </div>
        @endforelse

        {{-- ================= BARANG DALAM KERANJANG ================= --}}
        <h4 class="fw-bold mt-5 mb-3">
            <i class="bi bi-cart-check text-success me-2"></i>
            Barang dalam Keranjang
        </h4>

        @php
            $subtotal = 0;
        @endphp

        @foreach ($cartItems as $item)
            @if($item->type === 'paket')
                @php
                    $totalPerItem = $item->paket->harga_paket * $item->quantity;
                    $subtotal += $totalPerItem;
                @endphp

                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            {{-- Gambar --}}
                            <div class="col-md-2 text-center">
                                <img
                                    src="{{ asset('storage/'.$item->paket->image) }}"
                                    class="img-fluid rounded-3"
                                    style="height:90px;width:90px;object-fit:cover;">
                            </div>

                            {{-- Informasi --}}
                            <div class="col-md-7">
                                <span class="badge bg-success mb-2">
                                    Paket Hemat
                                </span>

                                <h5 class="fw-bold mb-1">
                                    {{ $item->paket->nama_paket }}
                                </h5>

                                <div class="text-muted">
                                    {{ $item->quantity }}
                                    Paket ×
                                    Rp {{ number_format($item->paket->harga_paket,0,',','.') }}
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="col-md-3 text-md-end">
                                <div class="text-muted small">
                                    Subtotal
                                </div>

                                <h5 class="fw-bold text-success mb-0">
                                    Rp {{ number_format($totalPerItem,0,',','.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                @php
                    $totalPerItem = $item->size->harga * $item->quantity;
                    $subtotal += $totalPerItem;
                @endphp
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            {{-- Gambar --}}
                            <div class="col-md-2 text-center">
                                <img
                                    src="{{ asset('storage/'.$item->produk->image) }}"
                                    class="img-fluid rounded-3"
                                    style="height:90px;width:90px;object-fit:cover;">
                            </div>

                            {{-- Informasi --}}
                            <div class="col-md-7">
                                <h5 class="fw-bold mb-1">
                                    {{ $item->produk->nama_produk }}
                                </h5>
                                <div class="text-muted mb-1">
                                    Ukuran :
                                    {{ $item->size->size }}
                                </div>
                                <div class="text-muted">
                                    {{ $item->quantity }}
                                    ×
                                    Rp {{ number_format($item->size->harga,0,',','.') }}
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="col-md-3 text-md-end">
                                <div class="text-muted small">
                                    Subtotal
                                </div>

                                <h5 class="fw-bold text-success mb-0">
                                    Rp {{ number_format($totalPerItem,0,',','.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
        <h5 class="fw-bold mt-4 mb-3">
            <i class="bi bi-clock-history text-success me-2"></i>
            Waktu Pengantaran
        </h5>

        <div class="list-group shadow-sm rounded-3">
            <label class="list-group-item list-group-item-action">
                <input
                    class="form-check-input me-2"
                    type="radio"
                    name="delivery_slot_id"
                    value=""
                    checked>
                <i class="bi bi-lightning-charge-fill text-warning me-2"></i>
                Secepatnya
            </label>

            @forelse($deliverySlots as $slot)
                <label class="list-group-item list-group-item-action">
                    <input
                        class="form-check-input me-2"
                        type="radio"
                        name="delivery_slot_id"
                        value="{{ $slot->id }}">
                    <i class="bi bi-clock text-success me-2"></i>
                    {{ substr($slot->waktu_mulai,0,5) }}
                    -
                    {{ substr($slot->waktu_selesai,0,5) }}
                </label>

            @empty
                <div class="list-group-item text-muted">
                    Tidak ada slot tersedia.
                    Pesanan akan dikirim secepatnya.
                </div>
            @endforelse
        </div>

        {{-- ================= PEMBAYARAN ================= --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">
                    <i class="bi bi-wallet2 text-success me-2"></i>
                    Pembayaran & Promo
                </h4>

                {{-- ================= METODE PEMBAYARAN ================= --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Metode Pembayaran
                    </label>
                    <select
                        name="metode_pembayaran"
                        class="form-select">
                        <option value="transfer">
                            💳 Transfer Bank
                        </option>
                        <option value="cod">
                            🚚 Bayar di Tempat (COD)
                        </option>
                    </select>
                </div>

                {{-- ================= PROMO ================= --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Gunakan Promo
                    </label>
                    <select
                        name="promo_id"
                        id="promoSelect"
                        class="form-select">
                        <option
                            value=""
                            data-diskon="0"
                            data-tipe="">
                            Tidak menggunakan promo
                        </option>
                        @foreach($promos as $promo)
                        <option
                            value="{{ $promo->id }}"
                            data-diskon="{{ $promo->diskon }}"
                            data-tipe="persen">
                            {{ $promo->nama_promo }}
                            ({{ $promo->diskon }}%)
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- ================= POIN ================= --}}
                <div>
                    <label class="form-label fw-semibold">
                        Gunakan Poin
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            ⭐
                        </span>

                        <input
                            type="number"
                            id="poinInput"
                            name="poin"
                            min="0"
                            max="{{ Auth::user()->jumlah_poin }}"
                            class="form-control"
                            placeholder="Maksimal {{ Auth::user()->jumlah_poin }} poin">
                        <span class="input-group-text">
                            Poin
                        </span>
                    </div>
                    <small class="text-muted">
                        1 poin = Rp100
                    </small>
                </div>
            </div>
        </div>

       {{-- ================= RINGKASAN PEMBAYARAN ================= --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4">
                    <i class="bi bi-receipt-cutoff text-success me-2"></i>
                    Ringkasan Pembayaran
                </h4>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">
                        Subtotal
                    </span>

                    <strong>
                        Rp
                        <span id="subtotalText">
                            {{ number_format($subtotal,0,',','.') }}
                        </span>
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">
                        Diskon Promo
                    </span>

                    <span class="text-danger">
                        - Rp
                        <span id="diskonPromoText">
                            0
                        </span>
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">
                        Diskon Poin
                    </span>

                    <span class="text-danger">
                        - Rp
                        <span id="diskonPoinText">
                            0
                        </span>
                    </span>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="text-muted">
                            Total Pembayaran
                        </div>

                        <small class="text-muted">
                            Sudah termasuk seluruh potongan
                        </small>

                    </div>
                    <h3 class="fw-bold text-success mb-0">
                        Rp
                        <span id="totalBayarText">
                            {{ number_format($subtotal,0,',','.') }}
                        </span>
                    </h3>
                </div>
                <input
                    type="hidden"
                    name="total_terhitung"
                    id="totalHidden"
                    value="{{ $subtotal }}">

                <div class="d-grid">
                    <button
                        type="submit"
                        class="btn btn-success btn-lg rounded-pill">
                        <i class="bi bi-credit-card me-2"></i>
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
{{-- SCRIPT DISKON --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const subtotal = @json($subtotal);
    const promoSelect = document.getElementById('promoSelect');
    const poinInput = document.getElementById('poinInput');

    const diskonPromoText = document.getElementById('diskonPromoText');
    const diskonPoinText = document.getElementById('diskonPoinText');
    const totalBayarText = document.getElementById('totalBayarText');
    const totalHidden = document.getElementById('totalHidden');

    function updateTotal() {
        const selected = promoSelect.options[promoSelect.selectedIndex];
        const tipe = selected.dataset.tipe;
        const nilai = parseFloat(selected.dataset.diskon) || 0;
        const poin = parseInt(poinInput.value) || 0;

        let diskonPromo = 0;
        if (tipe === 'persen') {
            diskonPromo = subtotal * (nilai / 100);
        }

        let diskonPoin = poin * 100;
        let total = subtotal - diskonPromo - diskonPoin;
        if (total < 0) total = 0;

        diskonPromoText.textContent = diskonPromo.toLocaleString('id-ID');
        diskonPoinText.textContent = diskonPoin.toLocaleString('id-ID');
        totalBayarText.textContent = total.toLocaleString('id-ID');
        totalHidden.value = total;
    }

    promoSelect.addEventListener('change', updateTotal);
    poinInput.addEventListener('input', updateTotal);

});
</script>
@endsection
