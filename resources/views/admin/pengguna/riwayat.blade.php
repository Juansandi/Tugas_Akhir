@extends('layouts.admin')

@section('title', 'Riwayat Transaksi Pengguna')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">Riwayat Transaksi - {{ $pengguna->username }}</h3>
    <p><strong>No. Telepon:</strong> {{ $pengguna->no_telp }}</p>
    <p><strong>Alamat:</strong> {{ $pengguna->alamat }}</p>
    <p><strong>Jumlah Poin Saat Ini:</strong> {{ $pengguna->jumlah_poin }}</p>

    @if($pesanan->isEmpty())
        <div class="alert alert-info mt-4">Pengguna ini belum memiliki transaksi.</div>
    @else
        @foreach ($pesanan as $order)
            @php
                $status = $order->status;
                $badgeClass = match($status) {
                    'menunggu konfirmasi' => 'bg-secondary text-light',
                    'diproses'            => 'bg-primary text-light',
                    'dikirim'             => 'bg-info text-dark',
                    'diterima'            => 'bg-warning text-dark',
                    'selesai'             => 'bg-success text-light',
                    default               => 'bg-light text-dark'
                };

                $subtotalKeseluruhan = 0;
            @endphp

            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Pesanan #{{ $order->id }}</strong>
                    <span class="badge {{ $badgeClass }} text-uppercase">{{ $order->status }}</span>
                </div>
                <div class="card-body">
                    <h6 class="mt-3">Detail Produk:</h6>
                    <ul class="list-group">
                        @foreach ($order->detail as $detail)
                            @php
                                $subtotalItem = $detail->quantity * $detail->price;
                                $subtotalKeseluruhan += $subtotalItem;
                            @endphp

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    @if($detail->type === 'produk' && $detail->produk)
                                        {{ $detail->produk->nama_produk }}
                                        <small class="text-muted">
                                            ({{ $detail->quantity }} x
                                            Rp {{ number_format($detail->price, 0, ',', '.') }})
                                        </small>
                                    @elseif($detail->type === 'paket' && $detail->paket)
                                        <strong>{{ $detail->paket->nama_paket }}</strong>
                                        <span class="badge bg-info ms-1">Paket</span>
                                        <div class="text-muted">
                                            ({{ $detail->quantity }} x
                                            Rp {{ number_format($detail->price, 0, ',', '.') }})
                                        </div>

                                        @if($detail->type === 'paket')
                                            <ul class="small text-muted mt-1">
                                                @foreach($detail->paket->detailPakets as $isi)
                                                    <li>
                                                        {{ $isi->produk->nama_produk }}
                                                        ({{ $isi->quantity }} {{ $isi->size->size }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endif
                                </div>

                                <div>
                                    <strong>
                                        Subtotal: Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4">
                        <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->metode_pembayaran) }}</p>
                        <p><strong>Total Harga Produk:</strong> Rp{{ number_format($subtotalKeseluruhan, 0, ',', '.') }}</p>
                        <p><strong>Poin Digunakan:</strong> {{ $order->poin_digunakan ?? 0 }}</p>
                        <p><strong>Diskon Poin:</strong> -Rp{{ number_format($order->diskon_dari_poin, 0, ',', '.') }}</p>
                        <p><strong>Diskon Promo:</strong> -Rp{{ number_format($order->diskon_dari_promo, 0, ',', '.') }}</p>
                        <p><strong>Poin Diperoleh:</strong> {{ $order->poin_diperoleh ?? 0 }}</p>
                        <p><strong>Total Bayar:</strong> Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">← Kembali ke Manajemen Pengguna</a>
</div>
@endsection
