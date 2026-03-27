@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Checkout</h4>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('pesanan.store') }}" method="POST" id="checkoutForm">
        @csrf

        <h5>Alamat Pengiriman</h5>
        @forelse ($alamatList as $alamat)
            <div class="form-check mb-2">
                <input type="radio"
                    name="alamat_id"
                    value="{{ $alamat->id }}"
                    class="form-check-input"
                    {{ $alamat->is_default ? 'checked' : '' }}>
                <label class="form-check-label">
                    <strong>{{ $alamat->label }}</strong><br>
                    {{ $alamat->alamat }}
                </label>
            </div>
        @empty
            <p class="text-muted">Belum ada alamat pengiriman.</p>
        @endforelse

        {{-- ITEM --}}
        <h5>Barang dalam Keranjang</h5>

        @php
            $subtotal = 0;
        @endphp

        @foreach ($cartItems as $item)
            @if($item->type === 'paket')
                @php
                    $totalPerItem = $item->paket->harga_paket * $item->quantity;
                    $subtotal += $totalPerItem;
                @endphp

                <div class="card mb-2 p-3">
                    <strong>{{ $item->paket->nama_paket }}</strong><br>
                    <span class="text-muted">Paket Produk</span><br>
                    {{ $item->quantity }} ×
                    Rp {{ number_format($item->paket->harga_paket,0,',','.') }}
                    =
                    <strong>Rp {{ number_format($totalPerItem,0,',','.') }}</strong>
                </div>

            @else
                @php
                    $totalPerItem = $item->size->harga * $item->quantity;
                    $subtotal += $totalPerItem;
                @endphp

                <div class="card mb-2 p-3">
                    <strong>{{ $item->produk->nama_produk }}</strong><br>
                    <span class="text-muted">Ukuran: {{ $item->size->size }}</span><br>
                    {{ $item->quantity }} ×
                    Rp {{ number_format($item->size->harga,0,',','.') }}
                    =
                    <strong>Rp {{ number_format($totalPerItem,0,',','.') }}</strong>
                </div>
            @endif
        @endforeach

        {{-- ========================= --}}
        {{-- SLOT PENGANTARAN --}}
        {{-- ========================= --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">
                Pilih Waktu Pengantaran
            </div>
            <div class="card-body">

                {{-- OPSI SECEPATNYA --}}
                <div class="form-check mb-2">
                    <input class="form-check-input"
                        type="radio"
                        name="delivery_slot_id"
                        id="slot_now"
                        value=""
                        checked>
                    <label class="form-check-label" for="slot_now">
                        Secepatnya
                    </label>
                </div>

                {{-- OPSI SLOT --}}
                @forelse ($deliverySlots as $slot)
                    <div class="form-check mb-2">
                        <input class="form-check-input"
                            type="radio"
                            name="delivery_slot_id"
                            id="slot_{{ $slot->id }}"
                            value="{{ $slot->id }}">
                        <label class="form-check-label" for="slot_{{ $slot->id }}">
                            {{ substr($slot->waktu_mulai,0,5) }} –
                            {{ substr($slot->waktu_selesai,0,5) }}
                        </label>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        Tidak ada slot waktu tersisa hari ini.
                        Pesanan akan dikirim secepatnya.
                    </p>
                @endforelse

            </div>
        </div>


        {{-- PEMBAYARAN --}}
        <h5 class="mt-4">Metode Pembayaran</h5>
        <select name="metode_pembayaran" class="form-select mb-3" required>
            <option value="transfer">Transfer</option>
            <option value="cod">Bayar di Tempat (COD)</option>
        </select>

        {{-- PROMO --}}
        <h5>Gunakan Promo</h5>
        <select name="promo_id" id="promoSelect" class="form-select mb-3">
            <option value="" data-diskon="0" data-tipe="">-- Tidak Menggunakan Promo --</option>
            @foreach ($promos as $promo)
                <option
                    value="{{ $promo->id }}"
                    data-diskon="{{ $promo->diskon }}"
                    data-tipe="persen"
                >
                    {{ $promo->nama_promo }} - {{ $promo->diskon }}%
                </option>
            @endforeach
        </select>

        {{-- POIN --}}
        <h5>Gunakan Poin</h5>
        <input type="number"
               name="poin"
               id="poinInput"
               min="0"
               max="{{ Auth::user()->jumlah_poin }}"
               class="form-control mb-3"
               placeholder="Maks {{ Auth::user()->jumlah_poin }} poin">

        {{-- RINGKASAN --}}
        <h5>Ringkasan</h5>
        <div class="border rounded p-3 mb-4">
            <p>Subtotal:
                <strong>Rp <span id="subtotalText">{{ number_format($subtotal, 0, ',', '.') }}</span></strong>
            </p>
            <p>Diskon Promo:
                Rp <span id="diskonPromoText">0</span>
            </p>
            <p>Diskon Poin:
                Rp <span id="diskonPoinText">0</span>
            </p>
            <h5>
                Total Bayar:
                <strong>Rp <span id="totalBayarText">{{ number_format($subtotal, 0, ',', '.') }}</span></strong>
            </h5>
        </div>

        {{-- TOTAL TERHITUNG (UX ONLY) --}}
        <input type="hidden" name="total_terhitung" id="totalHidden" value="{{ $subtotal }}">

        <button type="submit" class="btn btn-success w-100">
            Bayar Sekarang
        </button>
    </form>
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
