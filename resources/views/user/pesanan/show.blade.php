@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">
        Detail Pesanan
        <span class="text-muted">#{{ $pesanan->id }}</span>
    </h4>

    {{-- INFO PESANAN --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            {{-- STATUS --}}
            <h5>Status Pesanan</h5>
            <span class="badge {{ $pesanan->status_badge }} px-3 py-2">
                {{ $pesanan->status_label }}
            </span>

            {{-- AKSI --}}
            <div class="mt-3 d-flex gap-2 flex-wrap">

                @if($pesanan->status === 'belum_dibayar')
                    <a href="{{ route('pesanan.pembayaran', $pesanan->id) }}"
                       class="btn btn-warning">
                        Upload Bukti Pembayaran
                    </a>
                @endif

                @if(in_array($pesanan->status, ['diproses','dikirim','selesai']))
                    <a href="{{ route('chat.show', ['pesanan'=>$pesanan->id,'type'=>'admin']) }}"
                       class="btn btn-outline-primary position-relative">
                        💬 Chat Admin
                        @if(optional($pesanan->chatAdminUnreadForUser)->unread_count > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $pesanan->chatAdminUnreadForUser->unread_count }}
                            </span>
                        @endif
                    </a>
                @endif

                @if(in_array($pesanan->status, ['dikirim','selesai']))
                    <a href="{{ route('chat.show', ['pesanan'=>$pesanan->id,'type'=>'kurir']) }}"
                       class="btn btn-outline-success position-relative">
                        🚚 Chat Kurir
                        @if(optional($pesanan->chatKurirUnreadForUser)->unread_count > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $pesanan->chatKurirUnreadForUser->unread_count }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>

            {{-- RINCIAN HARGA --}}
            <hr>
            <h5>Perincian Harga</h5>
            @php
                $subtotal = $pesanan->detail->sum(fn($i) => $i->price * $i->quantity);
            @endphp
            <ul class="list-unstyled">
                <li>Subtotal: <strong>Rp {{ number_format($subtotal,0,',','.') }}</strong></li>
                <li>Diskon Promo: -Rp {{ number_format($pesanan->diskon_dari_promo ?? 0,0,',','.') }}</li>
                <li>Diskon Poin: -Rp {{ number_format($pesanan->diskon_dari_poin ?? 0,0,',','.') }}</li>
                @if(in_array($pesanan->status, ['diproses','dikirim','diterima','selesai']))
                    <li>
                        <strong>Total Dibayar:</strong>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </span>
                    </li>
                @else
                    <li>
                        <strong>Total Dibayar:</strong>
                        <span class="text-muted">Belum dibayar</span>
                    </li>
                @endif
            </ul>
            @if($pesanan->status === 'selesai')
                <hr>
                <h5>Poin Diperoleh</h5>
                <p class="mb-0">
                    🎉 Anda mendapatkan
                    <strong class="text-success">
                        {{ $pesanan->poin_diperoleh }} poin
                    </strong>
                    dari pesanan ini.
                </p>
            @endif

            {{-- ALAMAT --}}
            <h5 class="mt-4">Alamat Pengiriman</h5>
            <div class="border rounded p-3 bg-light">
                {{ $pesanan->alamat_pengiriman }}
                @if($pesanan->no_telp_pengiriman)
                    <br><small class="text-muted">No. Telp: {{ $pesanan->no_telp_pengiriman }}</small>
                @endif
            </div>

            @if(in_array($pesanan->status, ['dikirim','selesai']) && $pesanan->tugasKurir)
                <hr>
                <h5>Bukti Pengiriman</h5>

                @if($pesanan->tugasKurir->bukti_kirim)
                   <img src="{{ asset('storage/'.$pesanan->tugasKurir->bukti_kirim) }}"
                        class="img-fluid rounded border mb-2"
                        style="max-height:250px; object-fit:contain;">

                    @if($pesanan->tugasKurir->catatan_kurir)
                        <p class="text-muted">
                            <strong>Catatan Kurir:</strong><br>
                            {{ $pesanan->tugasKurir->catatan_kurir }}
                        </p>
                    @endif
                @else
                    <p class="text-muted">
                        Bukti pengiriman belum tersedia.
                    </p>
                @endif
            @endif

            {{-- KONFIRMASI TERIMA --}}
            @if ($pesanan->status === 'dikirim')
                <form method="POST" action="{{ route('pesanan.updateStatus', $pesanan->id) }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="status" value="diterima">
                    <button class="btn btn-success">
                        Konfirmasi Diterima
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- DETAIL PRODUK --}}
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

                    <div class="fw-semibold">
                        Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- NAVIGASI --}}
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
