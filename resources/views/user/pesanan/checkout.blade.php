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
        <p>{{ $alamat }}</p>

        <h5 class="mt-4">Item dalam Keranjang</h5>
        @php
            $subtotal = 0;
        @endphp
        @foreach ($cartItems as $item)
            @php
                $totalPerItem = $item->produk->harga * $item->quantity;
                $subtotal += $totalPerItem;
            @endphp
            <div class="card mb-2 p-2">
                <strong>{{ $item->produk->nama_produk }}</strong>
                {{ $item->quantity }} x Rp{{ number_format($item->produk->harga, 0, ',', '.') }} =
                <strong>Rp{{ number_format($totalPerItem, 0, ',', '.') }}</strong>
            </div>
        @endforeach

        <h5 class="mt-4">Metode Pembayaran</h5>
        <select name="metode_pembayaran" class="form-select mb-3" required>
            <option value="transfer">Transfer</option>
            <option value="cod">Bayar di Tempat (COD)</option>
        </select>

        <h5>Gunakan Promo</h5>
        <select name="promo_id" id="promoSelect" class="form-select mb-3">
            <option value="" data-diskon="0" data-tipe="">-- Tidak Menggunakan Promo --</option>
            @foreach ($promos as $promo)
                <option 
                    value="{{ $promo->id }}"
                    data-diskon="{{ $promo->diskon }}"
                    data-tipe="persen"
                >
                    {{ $promo->nama_promo }} - {{ $promo->diskon }}% off
                </option>
            @endforeach
        </select>

        <h5>Gunakan Poin</h5>
        <input type="number" name="poin" id="poinInput" min="0" max="{{ Auth::user()->jumlah_poin }}"
               class="form-control mb-3" placeholder="Masukkan jumlah poin (max {{ Auth::user()->jumlah_poin }})">

        <h5>Ringkasan</h5>
        <div id="ringkasanDiskon" class="border p-3 rounded mb-4">
            <p>Subtotal: <strong>Rp <span id="subtotalText">{{ number_format($subtotal, 0, ',', '.') }}</span></strong></p>
            <p>Diskon Promo: Rp <span id="diskonPromoText">0</span></p>
            <p>Diskon Poin: Rp <span id="diskonPoinText">0</span></p>
            <h5>Total Bayar: <strong>Rp <span id="totalBayarText">{{ number_format($subtotal, 0, ',', '.') }}</span></strong></h5>
        </div>

        <input type="hidden" id="totalHidden" name="total_terhitung" value="{{ $subtotal }}">

        <button type="submit" class="btn btn-success w-100">Bayar Sekarang</button>
    </form>
</div>

{{-- JavaScript Diskon --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subtotal = @json($subtotal);  // tambah tanda kurung
    const promoSelect = document.getElementById('promoSelect');
    const poinInput = document.getElementById('poinInput');
    const diskonPromoText = document.getElementById('diskonPromoText');
    const diskonPoinText = document.getElementById('diskonPoinText');
    const totalBayarText = document.getElementById('totalBayarText');
    const totalHidden = document.getElementById('totalHidden');

    function updateTotal() {
        const selectedOption = promoSelect.options[promoSelect.selectedIndex];
        const tipeDiskon = selectedOption.dataset.tipe;
        const nilaiDiskon = parseFloat(selectedOption.dataset.diskon) || 0;
        const poin = parseInt(poinInput.value) || 0;

        let diskonPromo = 0;
        if (tipeDiskon === 'persen') {
            diskonPromo = subtotal * (nilaiDiskon / 100);
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
