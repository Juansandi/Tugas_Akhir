@extends('layouts.kurir')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">

    <h4 class="mb-3">
        Detail Pesanan #{{ $tugas->pesanan->id }}
    </h4>

    {{-- STATUS --}}
    <span class="badge bg-info mb-3">
        {{ ucfirst($tugas->status) }}
    </span>

    {{-- WAKTU PENGANTARAN --}}
    <div class="card mb-3 border-info">
        <div class="card-body">
            <h6>Waktu Pengantaran</h6>

            @if ($tugas->pesanan->deliverySlot)
                <p class="mb-0 fw-semibold text-info">
                    {{ substr($tugas->pesanan->deliverySlot->waktu_mulai,0,5) }}
                    –
                    {{ substr($tugas->pesanan->deliverySlot->waktu_selesai,0,5) }}
                </p>
            @else
                <p class="mb-0 text-muted">
                    Secepatnya
                </p>
            @endif
        </div>
    </div>

    {{-- Nama Customer --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Nama Pembeli</h6>
            <p class="mb-0 fw-semibold">
                {{ $tugas->pesanan->pengguna->username ?? '-' }}
            </p>
        </div>
    </div>

   {{-- ALAMAT PENGIRIMAN --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Alamat Pengiriman</h6>

            @if (!empty($tugas->pesanan->alamat_pengiriman))
                <p class="mb-1">
                    {{ $tugas->pesanan->alamat_pengiriman }}
                </p>

                @if (!empty($tugas->pesanan->no_telp_pengiriman))
                    <small class="text-muted">
                        No. Telp: {{ $tugas->pesanan->no_telp_pengiriman }}
                    </small>
                @endif
            @else
                <p class="text-muted mb-0">
                    Alamat pengiriman tidak tersedia
                </p>
            @endif
        </div>
    </div>

    {{-- PRODUK --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Produk Pesanan</h6>

            <ul class="list-group list-group-flush">
            @foreach($tugas->pesanan->detail as $item)
                <li class="list-group-item">
                    @if($item->type === 'produk')
                        {{ $item->produk->nama_produk }}
                        ({{ $item->size->size }})
                        × {{ $item->quantity }}
                    @else
                        {{ $item->paket->nama_paket }}
                        × {{ $item->quantity }}
                        <span class="badge bg-info ms-2">Paket</span>
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="card mb-3">
        <div class="card-body">
            <strong>Total Pesanan:</strong>
            Rp {{ number_format($tugas->pesanan->total, 0, ',', '.') }}
        </div>
    </div>

    @if($tugas->status === 'selesai' && $tugas->bukti_kirim)
    <div class="card mt-4 border-success">
        <div class="card-body">
            <h6 class="fw-semibold text-success mb-3">
                Bukti Pengiriman
            </h6>

            <img src="{{ asset('storage/'.$tugas->bukti_kirim) }}"
                class="img-fluid rounded border mb-2"
                style="max-height:250px">

            <p class="mb-1">
                <strong>Waktu Kirim:</strong>
                {{ optional($tugas->waktu_kirim)->format('d M Y H:i') }}
            </p>

            @if($tugas->catatan_kurir)
                <p class="mb-0">
                    <strong>Catatan: </strong>
                    {{ $tugas->catatan_kurir }}
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- AKSI KURIR --}}
    @if($tugas->status === 'aktif')
    <div class="card mt-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Bukti Pengiriman</h6>

            <form method="POST"
                action="{{ route('kurir.kirim', $tugas->id) }}"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Foto Bukti Kirim</label>
                    <input type="file"
                        name="bukti_kirim"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan Kurir (opsional)</label>
                    <textarea name="catatan_kurir"
                            class="form-control"
                            rows="2"
                            placeholder="Contoh: paket dititip satpam"></textarea>
                </div>

                <button class="btn btn-success w-100">
                    🚚 Tandai Sudah Dikirim
                </button>
            </form>
        </div>
    </div>
    @endif
    {{-- TOMBOL KEMBALI --}}
    <div class="mt-4">
        @if($tugas->status === 'aktif')
            <a href="{{ route('kurir.pesanan') }}"
            class="btn btn-outline-secondary w-100">
                ← Kembali ke Pesanan Aktif
            </a>
        @else
            <a href="{{ route('kurir.riwayat') }}"
            class="btn btn-outline-secondary w-100">
                ← Kembali ke Riwayat Pengiriman
            </a>
        @endif
    </div>

</div>
@endsection
