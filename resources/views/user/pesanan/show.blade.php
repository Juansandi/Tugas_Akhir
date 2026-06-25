@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">
        Detail Pesanan
        <span class="text-muted">#{{ $pesanan->id }}</span>
    </h4>

    {{-- ================= INFO PESANAN ================= --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            {{-- STATUS --}}
            <h6>Status Pesanan</h6>
            <span class="badge {{ $pesanan->status_badge }} px-4 py-2 fs-6">
                {{ $pesanan->status_label }}
            </span>

            {{-- ================= WAKTU PENGANTARAN ================= --}}
            <div class="mt-3">
                <h6>Waktu Pengantaran</h6>

                @if ($pesanan->deliverySlot)
                    <span class="badge bg-info px-3 py-2">
                        {{ substr($pesanan->deliverySlot->waktu_mulai,0,5) }}
                        –
                        {{ substr($pesanan->deliverySlot->waktu_selesai,0,5) }}
                    </span>
                @else
                    <span class="text-muted">
                        Secepatnya
                    </span>
                @endif
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
                💬 Chat dengan admin dan kurir masih tersedia hingga 24 jam setelah pesanan selesai.
            </div>
            @endif

            @if($pesanan->status === 'selesai' && !$pesanan->chatMasihAktif())
            <div class="alert-muted small mt-3">
                ⛔ Waktu chat telah berakhir (maksimal 24 jam setelah pesanan selesai).
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

            {{-- ================= RINCIAN HARGA ================= --}}
            <hr>
            <h5>Perincian Harga</h5>
            @php
                $subtotal = $pesanan->detail->sum(fn($i) => $i->price * $i->quantity);
            @endphp
            <ul class="list-unstyled">
                <li>Subtotal: <strong>Rp {{ number_format($subtotal,0,',','.') }}</strong></li>
                <li>Diskon Promo: -Rp {{ number_format($pesanan->diskon_dari_promo ?? 0,0,',','.') }}</li>
                <li>Diskon Poin: -Rp {{ number_format($pesanan->diskon_dari_poin ?? 0,0,',','.') }}</li>
                <li>
                    <strong>Total Dibayar:</strong>
                    <span class="fw-bold text-success">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </span>
                </li>
            </ul>

            {{-- ================= ALAMAT ================= --}}
            <h5 class="mt-4">Alamat Pengiriman</h5>
            <div class="border rounded p-3 bg-light">
                {{ $pesanan->alamat_pengiriman }}
                @if($pesanan->no_telp_pengiriman)
                    <br><small class="text-muted">No. Telp: {{ $pesanan->no_telp_pengiriman }}</small>
                @endif
            </div>

        </div>
    </div>

    {{-- ================= DETAIL PRODUK ================= --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Produk dalam Pesanan</h5>

            @foreach($pesanan->detail as $item)
                <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                    <div class="d-flex align-items-center">
                        @php
                            $image = $item->type === 'paket'
                                ? optional($item->paket)->image
                                : optional($item->produk)->image;
                        @endphp

                        @if($image)
                            <img src="{{ asset('storage/'.$image) }}"
                                style="width:64px;height:64px;object-fit:cover;border-radius:8px"
                                class="me-3">
                        @endif

                        <div>
                            <div class="fw-bold">
                                {{ $item->type === 'paket'
                                    ? $item->paket->nama_paket
                                    : $item->produk->nama_produk }}
                            </div>

                            <small class="text-muted">
                                Jumlah: {{ $item->quantity }}
                                @if($item->type === 'produk')
                                    | Ukuran: {{ optional($item->size)->size }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="text-end">

                        <div class="fw-semibold mb-2">
                            Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
                        </div>

                        @if($pesanan->status === 'selesai' && $item->type === 'produk' && !$pesanan->refund)
                            @if(in_array($item->produk_id, $reviewedProdukIds))
                                <span class="badge bg-success">
                                    Sudah diulas
                                </span>
                            @else
                                <a href="{{ route('review.form', [
                                        'produk' => $item->produk_id,
                                        'pesanan' => $pesanan->id
                                    ]) }}"
                                class="btn btn-sm btn-outline-success">
                                    ✍️ Beri Ulasan
                                </a>
                            @endif
                        @endif

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
