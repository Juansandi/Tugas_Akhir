@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail Pesanan #{{ $pesanan->id }}</h4>
        <a href="{{ route('pesanan.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <p><strong>Nama Pembeli:</strong> {{ $pesanan->pengguna->username ?? 'Guest' }}</p>

    @php
        $status = $pesanan->status;
        $badgeClass = match($status) {
            'menunggu konfirmasi' => 'bg-secondary text-light',
            'diproses'            => 'bg-primary text-light',
            'dikirim'             => 'bg-info text-dark',
            'diterima'            => 'bg-warning text-dark',
            'selesai'             => 'bg-success text-light',
            default               => 'bg-light text-dark'
        };

        $subtotal = $pesanan->detail->sum(fn($item) => $item->price * $item->quantity);
        $diskonPromo = $pesanan->diskon_dari_promo ?? 0;
        $diskonPoin = $pesanan->diskon_dari_poin ?? 0;
        $totalSetelahDiskon = $subtotal - $diskonPromo - $diskonPoin;
    @endphp

    <p><strong>Status:</strong> <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span></p>
    <div class="mt-2 d-flex gap-2 flex-wrap">
    @if(in_array($pesanan->status, ['diproses', 'dikirim', 'selesai']))
        <a href="{{ route('admin.chat.show', $pesanan->chatAdmin->id) }}"
        class="btn btn-outline-primary btn-sm mt-2">
            💬 Chat Customer

            @if(optional($pesanan->chatAdmin)->unread_count > 0)
                <span class="badge bg-danger ms-2">
                    {{ $pesanan->chatAdmin->unread_count }} pesan baru
                </span>
            @endif
        </a>
    @endif

    </div>
    <h5 class="mt-4">Perincian Harga</h5>
    <ul class="list-unstyled ps-3">
        <li class="mb-1"><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Diskon Promo:</strong> -Rp {{ number_format($diskonPromo, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Diskon Poin:</strong> -Rp {{ number_format($diskonPoin, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Total Setelah Diskon:</strong> Rp {{ number_format($totalSetelahDiskon, 0, ',', '.') }}</li>
        <li class="mb-1"><strong>Total Dibayar:</strong> <span class="fw-bold text-success">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span></li>
    </ul>

    @if ($pesanan->status === 'menunggu konfirmasi')
        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
            @csrf
            <input type="hidden" name="status" value="diproses">
            <button type="submit" class="btn btn-primary mt-3">Proses Pesanan</button>
        </form>
    @elseif ($pesanan->status === 'diproses')
        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
            @csrf
            <div class="mb-3 mt-3">
                <label class="form-label">Pilih Kurir</label>
                <select name="kurir_id" class="form-select" required>
                    <option value="">-- Pilih Kurir --</option>
                    @foreach($kurirs as $kurir)
                        <option value="{{ $kurir->id }}">
                            {{ $kurir->username }}
                        </option>
                    @endforeach
                </select>

                <input type="hidden" name="status" value="dikirim">
            </div>

            <button type="submit" class="btn btn-success">
                Tugaskan Kurir & Kirim Pesanan
            </button>
        </form>
    @elseif ($pesanan->status === 'dikirim')
        <p class="mt-3"><strong>Info Pengiriman:</strong> {{ $pesanan->no_resi ?? '-' }}</p>
    @elseif ($pesanan->status === 'diterima')
        <p class="mt-3"><strong>Status pesanan telah diterima oleh pembeli.</strong></p>

        <form method="POST" action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
            @csrf
            <input type="hidden" name="status" value="selesai">
            <button type="submit" class="btn btn-success mt-3">Selesaikan Pesanan</button>
        </form>
    @endif

    <hr class="my-4">

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
