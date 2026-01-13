@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:720px">

    <h4 class="fw-bold mb-3">
        Ajukan Refund
        <span class="text-muted">Pesanan #{{ $pesanan->id }}</span>
    </h4>

    @php
        // ================= REFUND WINDOW 24 JAM =================
        $lewatMenit = $pesanan->updated_at->diffInMinutes(now());
        $sisaJam = max(0, floor((24 * 60 - $lewatMenit) / 60));
    @endphp

    {{-- INFO REFUND WINDOW --}}
    <div class="alert alert-warning d-flex align-items-start">
        <div class="me-2">⏱️</div>
        <div>
            <strong>Batas waktu refund:</strong><br>
            @if($sisaJam > 0)
                Anda masih dapat mengajukan refund dalam
                <strong>{{ $sisaJam }} jam</strong>
                sejak pesanan dinyatakan selesai.
            @else
                <span class="text-danger">
                    Batas waktu pengajuan refund telah berakhir.
                </span>
            @endif
        </div>
    </div>

    {{-- JIKA SUDAH LEWAT BATAS --}}
    @if($sisaJam <= 0)
        <div class="alert alert-secondary">
            ⛔ Refund tidak dapat diajukan karena telah melewati batas waktu 1×24 jam.
        </div>

        <a href="{{ route('pesanan.show', $pesanan->id) }}"
           class="btn btn-outline-secondary">
            ← Kembali ke Detail Pesanan
        </a>
    @else

    {{-- ================= FORM REFUND ================= --}}
    <form method="POST"
          action="{{ route('refund.store', $pesanan->id) }}"
          enctype="multipart/form-data"
          class="card shadow-sm">

        @csrf

        <div class="card-body">

            {{-- ALASAN --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Alasan Refund <span class="text-danger">*</span>
                </label>
                <select name="alasan"
                        id="alasanSelect"
                        class="form-select"
                        required>
                    <option value="">-- Pilih Alasan --</option>
                    <option value="Produk tidak sesuai deskripsi">Produk tidak sesuai deskripsi</option>
                    <option value="Produk rusak atau cacat">Produk rusak atau cacat</option>
                    <option value="Produk tidak lengkap">Produk tidak lengkap</option>
                    <option value="Produk tidak sampai">Produk tidak sampai</option>
                    <option value="Salah pengiriman produk">Salah pengiriman produk</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- ALASAN LAINNYA --}}
            <div class="mb-3 d-none" id="alasanLainnyaGroup">
                <label class="form-label fw-semibold">
                    Jelaskan Alasan Lainnya
                </label>
                <textarea name="alasan_lainnya"
                          class="form-control"
                          rows="3"
                          placeholder="Tuliskan penjelasan singkat..."></textarea>
            </div>

            {{-- BUKTI --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Bukti Foto (opsional)
                </label>
                <input type="file"
                       name="bukti_foto"
                       class="form-control"
                       accept="image/*">
                <small class="text-muted">
                    Format JPG/PNG, maksimal 2MB
                </small>
            </div>

            {{-- METODE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Metode Refund <span class="text-danger">*</span>
                </label>
                <select name="metode_refund"
                        class="form-select"
                        required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Bank BCA">Bank BCA</option>
                    <option value="Bank Mandiri">Bank Mandiri</option>
                    <option value="OVO">OVO</option>
                    <option value="GoPay">GoPay</option>
                </select>
            </div>

            {{-- NOMOR TUJUAN --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Nomor Rekening / E-Wallet <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="nomor_tujuan"
                       class="form-control"
                       placeholder="Contoh: 08123456789 / 1234567890"
                       required>
            </div>

            {{-- ACTION --}}
            <div class="d-flex gap-2">
                <a href="{{ route('pesanan.show', $pesanan->id) }}"
                   class="btn btn-outline-secondary w-50">
                    Batal
                </a>
                <button type="submit"
                        class="btn btn-primary w-50">
                    Kirim Permintaan Refund
                </button>
            </div>

        </div>
    </form>
    @endif
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('alasanSelect');
    const lainnya = document.getElementById('alasanLainnyaGroup');

    select.addEventListener('change', function () {
        if (this.value === 'Lainnya') {
            lainnya.classList.remove('d-none');
        } else {
            lainnya.classList.add('d-none');
        }
    });
});
</script>
@endsection
