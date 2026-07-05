@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">
        Detail Pesanan
        <span class="text-muted">#{{ $pesanan->id }}</span>
    </h4>

    {{-- ================= INFO PESANAN ================= --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row">
                {{-- STATUS --}}
                <div class="col-lg-6">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-box-seam text-success me-2"></i>
                        Status Pesanan
                    </h5>

                    <span class="badge {{ $pesanan->status_badge }} px-4 py-3 fs-6 rounded-pill">
                        {{ $pesanan->status_label }}
                    </span>

                    <div class="mt-4">
                        <h6 class="fw-semibold">
                            <i class="bi bi-clock-history me-2 text-success"></i>
                            Waktu Pengantaran
                        </h6>

                        @if ($pesanan->deliverySlot)
                            <span class="badge bg-info rounded-pill px-3 py-2">
                                {{ substr($pesanan->deliverySlot->waktu_mulai,0,5) }}
                                -
                                {{ substr($pesanan->deliverySlot->waktu_selesai,0,5) }}
                            </span>

                        @else
                            <span class="text-muted">
                                Secepatnya
                            </span>
                        @endif
                    </div>
                </div>

                {{-- INFO PESANAN --}}
                <div class="col-lg-6">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-receipt text-success me-2"></i>
                        Informasi Pesanan
                    </h5>

                    <table class="table table-borderless mb-0">

                        <tr>
                            <td width="170" class="text-muted">Nomor Pesanan</td>
                            <td><strong>#{{ $pesanan->id }}</strong></td>
                        </tr>

                        <tr>
                            <td class="text-muted">Tanggal Pesanan</td>
                            <td>{{ $pesanan->created_at->format('d M Y H:i') }}</td>
                        </tr>

                        <tr>
                            <td class="text-muted">Metode Pembayaran</td>
                            <td>{{ strtoupper($pesanan->metode_pembayaran) }}</td>
                        </tr>

                        <tr>
                            <td class="text-muted">Kurir</td>
                            <td>
                                @if($pesanan->tugasKurir && $pesanan->tugasKurir->kurir)
                                    <strong>
                                        {{ $pesanan->tugasKurir->kurir->username }}
                                    </strong>

                                    @if($pesanan->tugasKurir->kurir->no_telp)
                                        <span class="text-muted ms-2">
                                            <i class="bi bi-telephone-fill me-1"></i>
                                            {{ $pesanan->tugasKurir->kurir->no_telp }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">
                                        Belum ditugaskan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- ================= AKSI ================= --}}
            <div class="mt-3 d-flex gap-2 flex-wrap">

                @if($pesanan->status === 'belum_dibayar')
                    <a href="{{ route('pesanan.pembayaran', $pesanan->id) }}"
                       class="btn btn-warning">
                        Unggah Bukti Pembayaran
                    </a>
                @endif

                @if(in_array($pesanan->status, ['diproses','dikirim','selesai']))   
                    <a href="{{ route('chat.show', [
                                'pesanan' => $pesanan->id,
                                'type' => 'admin'
                            ]) }}"
                       class="btn btn-outline-primary position-relative">
                        💬 Pesan Admin
                        @if(optional($pesanan->chatAdminUnreadForUser)->unread_count > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $pesanan->chatAdminUnreadForUser->unread_count }}
                            </span>
                        @endif
                    </a>
                @endif

               @if(in_array($pesanan->status, ['dikirim','selesai']))
                    <a href="{{ route('chat.show', [
                                'pesanan' => $pesanan->id,
                                'type' => 'kurir'
                            ]) }}"
                    class="btn btn-outline-success position-relative">
                        🚚 Pesan Kurir
                        @if(optional($pesanan->chatKurirUnreadForUser)->unread_count > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $pesanan->chatKurirUnreadForUser->unread_count }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>

            @if($pesanan->status === 'selesai' && $pesanan->chatMasihAktif())
            <div class="text-muted small mt-2">
                💬 Pesan dengan admin dan kurir masih tersedia hingga 24 jam setelah pesanan selesai.
            </div>
            @endif

            @if($pesanan->status === 'selesai' && !$pesanan->chatMasihAktif())
            <div class="alert-muted small mt-3">
                ⛔ Waktu pesan telah berakhir (maksimal 24 jam setelah pesanan selesai).
            </div>
            @endif

            {{-- ================= REFUND INFO ================= --}}
            @if($pesanan->status === 'selesai')
                @php
                    // ===== REFUND WINDOW 24 JAM (ANTI DESIMAL) =====
                    $lewatMenit = $pesanan->selesai_at->diffInMinutes(now());
                    $sisaJam   = max(0, floor((24 * 60 - $lewatMenit) / 60));
                @endphp

                <div class="mt-4">

                    {{-- REFUND COUNTDOWN --}}
                    @if(!$pesanan->refund && $sisaJam > 0)
                        <div class="alert alert-warning d-flex align-items-center">
                            ⏱️
                            <div class="ms-1">
                                Anda masih dapat mengajukan pengembalian dana dalam
                                <strong>{{ $sisaJam }} jam</strong>
                                setelah pesanan selesai.
                            </div>
                        </div>
                    @endif

                    {{-- REFUND EXPIRED --}}
                    @if(!$pesanan->refund && $sisaJam <= 0)
                        <div class="alert alert-secondary">
                            ⛔ Batas waktu pengajuan pengembalian dana telah berakhir
                            (maksimal 1×24 jam setelah pesanan selesai).
                        </div>
                    @endif

                    {{-- TOMBOL REFUND --}}
                    @if(!$pesanan->refund && $sisaJam > 0)
                        <a href="{{ route('refund.create', ['pesanan_id' => $pesanan->id]) }}"
                           class="btn btn-warning">
                            Ajukan Pengembalian Dana
                        </a>
                    @elseif($pesanan->refund)
                        <a href="{{ route('refund.show', $pesanan->refund->id) }}"
                           class="btn btn-outline-info">
                            Lihat Detail Pengembalian Dana
                        </a>
                    @endif
                </div>
            @endif

            @if($pesanan->status === 'selesai')
                {{-- BELUM DAPAT POIN --}}
                @if(!$pesanan->poin_sudah_diberikan)
                    <div class="alert alert-info mt-3">
                        🎁 Poin akan diberikan setelah 24 jam (masa pengembalian dana).
                    </div>
                @endif

                {{-- SUDAH DAPAT POIN --}}
                @if($pesanan->poin_sudah_diberikan)
                    <div class="alert alert-success mt-3">
                        🎉 Anda mendapatkan <strong>{{ $pesanan->poin_diperoleh }}</strong> poin dari pesanan ini.
                    </div>
                @endif
            @endif

           {{-- ================= RINGKASAN PEMBAYARAN ================= --}}
            <hr class="my-4">

            @php
                $subtotal = $pesanan->detail->sum(fn($i) => $i->price * $i->quantity);
            @endphp

            <div class="card border-0 bg-light rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-receipt-cutoff text-success me-2"></i>
                        Ringkasan Pembayaran
                    </h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Subtotal
                        </span>

                        <strong>
                            Rp {{ number_format($subtotal,0,',','.') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Diskon Promo
                        </span>
                        <span class="text-danger">
                            - Rp {{ number_format($pesanan->diskon_dari_promo ?? 0,0,',','.') }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Diskon Poin
                        </span>
                        <span class="text-danger">
                            - Rp {{ number_format($pesanan->diskon_dari_poin ?? 0,0,',','.') }}
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">
                                Total Pembayaran
                            </div>

                            <small class="text-muted">
                                Total yang telah dibayarkan
                            </small>
                        </div>

                        <h3 class="fw-bold text-success mb-0">
                            Rp {{ number_format($pesanan->total,0,',','.') }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- ================= ALAMAT PENGIRIMAN ================= --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                        Alamat Pengiriman
                    </h5>

                    <div class="mb-3">
                        <div class="fw-semibold mb-2">
                            Tujuan Pengiriman
                        </div>
                        <div class="text-muted lh-lg">
                            {{ $pesanan->alamat_pengiriman }}
                        </div>
                    </div>

                    @if($pesanan->no_telp_pengiriman)
                        <hr>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-success me-2"></i>
                            <span>{{ $pesanan->no_telp_pengiriman }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================= DETAIL PRODUK ================= --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-bag-check text-success me-2"></i>
                Produk dalam Pesanan
            </h4>

            @foreach($pesanan->detail as $item)
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        {{-- FOTO --}}
                        <div class="col-md-2 text-center">
                            @php
                                $image = $item->type === 'paket'
                                    ? optional($item->paket)->image
                                    : optional($item->produk)->image;
                            @endphp

                            @if($image)
                                <img
                                    src="{{ asset('storage/'.$image) }}"
                                    class="img-fluid rounded"
                                    style="height:90px;width:90px;object-fit:cover;">
                            @endif
                        </div>

                        {{-- INFO --}}
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-1">
                                {{ $item->type === 'paket'
                                    ? $item->paket->nama_paket
                                    : $item->produk->nama_produk }}
                            </h5>

                            @if($item->type == 'produk')
                                <span class="badge bg-light text-dark border mb-2">
                                    {{ optional($item->size)->size }}
                                </span>

                            @else
                                <span class="badge bg-success">
                                    Paket Produk
                                </span>
                            @endif

                            <div class="text-muted small">
                                Jumlah :
                                <strong>{{ $item->quantity }}</strong>
                            </div>

                            <div class="text-muted small">
                                Harga Satuan :
                                Rp {{ number_format($item->price,0,',','.') }}
                            </div>
                        </div>

                        {{-- SUBTOTAL --}}
                        <div class="col-md-2 text-center">
                            <div class="small text-muted">
                                Subtotal
                            </div>

                            <h5 class="fw-bold text-success mb-0">
                                Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
                            </h5>
                        </div>

                        {{-- AKSI --}}
                        <div class="col-md-2 text-end">
                            @if($pesanan->status === 'selesai'
                                && $item->type === 'produk'
                                && !$pesanan->refund)

                                @if(in_array($item->produk_id,$reviewedProdukIds))
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Sudah Diulas
                                    </span>
                                @else
                                    <a
                                        href="{{ route('review.form',[
                                            'produk'=>$item->produk_id,
                                            'pesanan'=>$pesanan->id
                                        ]) }}"
                                        class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Beri Ulasan
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ================= NAVIGASI ================= --}}
    <div class="d-flex gap-3 mt-4">
        <a href="{{ route('pesanan.history') }}" class="btn btn-outline-primary w-100">
            Riwayat Pesanan
        </a>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary w-100">
            Kembali ke Produk
        </a>
    </div>
</div>
@endsection
