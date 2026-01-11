@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Detail Pesanan #{{ $pesanan->id }}</h4>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    {{-- INFO PEMBELI --}}
    <p><strong>Nama Pembeli:</strong> {{ $pesanan->pengguna->username ?? '-' }}</p>

    {{-- ALAMAT --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Alamat Pengiriman</h6>
            <p class="mb-1">{{ $pesanan->alamat_pengiriman }}</p>
            @if($pesanan->no_telp_pengiriman)
                <small class="text-muted">No. Telp: {{ $pesanan->no_telp_pengiriman }}</small>
            @endif
        </div>
    </div>

    {{-- STATUS --}}
    @php
        $status = $pesanan->status;
        $badge = match($status) {
            'belum_dibayar'        => 'bg-danger',
            'menunggu_konfirmasi'  => 'bg-warning',
            'diproses'             => 'bg-primary',
            'dikirim'              => 'bg-info',
            'diterima'             => 'bg-secondary',
            'selesai'              => 'bg-success',
            'dibatalkan'           => 'bg-dark',
            default                => 'bg-light'
        };

        $subtotal = $pesanan->detail->sum(fn($item) => $item->price * $item->quantity);
        $diskonPromo = $pesanan->diskon_dari_promo ?? 0;
        $diskonPoin = $pesanan->diskon_dari_poin ?? 0;
        $totalSetelahDiskon = $subtotal - $diskonPromo - $diskonPoin;
    @endphp

    <p>
        <strong>Status:</strong>
        <span class="badge {{ $badge }}">{{ strtoupper(str_replace('_',' ',$status)) }}</span>
    </p>

    {{-- BATAS WAKTU PEMBAYARAN --}}
    @if($status === 'belum_dibayar')
        <p class="text-danger">
            ⚠ Batas upload bukti bayar:
            {{ $pesanan->created_at->addHours(24)->format('d M Y H:i') }}
        </p>
    @endif

    {{-- BUKTI BAYAR --}}
    @if($status === 'menunggu_konfirmasi')
        <div class="card mb-3">
            <div class="card-body">
                <h6>Bukti Pembayaran</h6>
               <img src="{{ asset('storage/'.$pesanan->bukti_bayar) }}"
     class="img-fluid rounded border"
     style="max-height:300px">

                <form method="POST" action="{{ route('admin.pesanan.verifikasi', $pesanan->id) }}">
                    @csrf
                    <button name="aksi" value="terima" class="btn btn-success">Verifikasi</button>
                    <button name="aksi" value="tolak" class="btn btn-danger">Tolak</button>
                </form>
            </div>
        </div>
    @endif

    <h5 class="mt-4">Perincian Harga</h5>
    <ul class="list-unstyled ps-3">
        <li class="mb-1"><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Diskon Promo:</strong> -Rp {{ number_format($diskonPromo, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Diskon Poin:</strong> -Rp {{ number_format($diskonPoin, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Total Setelah Diskon:</strong> Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Total Dibayar:</strong> <span class="fw-bold text-success">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span></li>
    </ul>

    {{-- AKSI STATUS --}}
    @if($status === 'diproses')
        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
            @csrf
            <input type="hidden" name="status" value="dikirim">

            <label>Pilih Kurir</label>
            <select name="kurir_id" class="form-select mb-2" required>
                <option value="">-- Pilih Kurir --</option>
                @foreach($kurirs as $kurir)
                    <option value="{{ $kurir->id }}">{{ $kurir->username }}</option>
                @endforeach
            </select>

            <button class="btn btn-success">Kirim Pesanan</button>
        </form>
    @endif

    @if($status === 'diterima')
        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
            @csrf
            <input type="hidden" name="status" value="selesai">
            <button class="btn btn-success mt-3">Selesaikan Pesanan</button>
        </form>
    @endif

    <hr>

     <h5>Detail Produk</h5>
    <div class="list-group">
    @foreach($pesanan->detail as $item)
        <div class="list-group-item py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">

                    {{-- IMAGE --}}
                    @if($item->type === 'produk' && $item->produk)
                        <img src="{{ asset('storage/'.$item->produk->image) }}"
                            alt="{{ $item->produk->nama_produk }}"
                            style="width:64px;height:64px;object-fit:cover;border-radius:8px;margin-right:16px;">
                    @elseif($item->type === 'paket' && $item->paket)
                        <img src="{{ asset('storage/'.$item->paket->image) }}"
                            alt="{{ $item->paket->nama_paket }}"
                            style="width:64px;height:64px;object-fit:cover;border-radius:8px;margin-right:16px;">
                    @endif

                    {{-- INFO --}}
                    <div>
                        @if($item->type === 'produk')
                            <div class="fw-bold">{{ $item->produk->nama_produk }}</div>
                            <small class="text-muted">
                                Ukuran: {{ $item->size->size ?? '-' }} <br>
                                Jumlah: {{ $item->quantity }}
                            </small>
                        @else
                            <div class="fw-bold">
                                {{ $item->paket->nama_paket }}
                                <span class="badge bg-info ms-1">Paket</span>
                            </div>
                            <small class="text-muted">
                                Jumlah Paket: {{ $item->quantity }}
                            </small>
                        @endif
                    </div>

                </div>

                {{-- HARGA --}}
                <div class="text-end">
                    <span class="fw-semibold">
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
