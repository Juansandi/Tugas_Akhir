@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Ajukan Refund untuk Pesanan #{{ $pesanan->id }}</h4>

    <form method="POST" action="{{ route('refund.store', $pesanan->id) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Refund</label>
            <select name="alasan" id="alasanSelect" class="form-select" required>
                <option value="">-- Pilih Alasan --</option>
                <option value="Produk tidak sesuai deskripsi">Produk tidak sesuai deskripsi</option>
                <option value="Produk rusak atau cacat">Produk rusak atau cacat</option>
                <option value="Produk tidak lengkap">Produk tidak lengkap</option>
                <option value="Produk tidak sampai">Produk tidak sampai</option>
                <option value="Salah pengiriman produk">Salah pengiriman produk</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="mb-3" id="alasanLainnyaGroup" style="display: none;">
            <label for="alasan_lainnya" class="form-label">Tulis Alasan Lainnya</label>
            <textarea name="alasan_lainnya" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="bukti_foto" class="form-label">Bukti Foto</label>
            <input type="file" name="bukti_foto" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="metode_refund" class="form-label">Metode Refund</label>
            <select name="metode_refund" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Bank BCA">Bank BCA</option>
                <option value="Bank Mandiri">Bank Mandiri</option>
                <option value="OVO">OVO</option>
                <option value="GoPay">GoPay</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nomor_tujuan" class="form-label">Nomor Tujuan</label>
            <input type="text" name="nomor_tujuan" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Permintaan Refund</button>
    </form>
</div>

<!-- Script agar textarea muncul saat pilih "Lainnya" -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alasanSelect = document.getElementById('alasanSelect');
        const alasanLainnyaGroup = document.getElementById('alasanLainnyaGroup');

        alasanSelect.addEventListener('change', function () {
            if (this.value === 'Lainnya') {
                alasanLainnyaGroup.style.display = 'block';
            } else {
                alasanLainnyaGroup.style.display = 'none';
            }
        });
    });
</script>
@endsection
