@extends('layouts.kurir')

@section('title', 'Detail Pesanan')

@section('content')

@php
    $badge = match($tugas->status){
        'aktif' => 'bg-primary',
        'selesai' => 'bg-success',
        default => 'bg-secondary'
    };
@endphp

<div class="container py-4">
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            Detail Pesanan #{{ $tugas->pesanan->id }}
        </h3>

        <p class="text-muted mb-2">
            Informasi lengkap pesanan yang menjadi tanggung jawab Anda.
        </p>

        <span class="badge {{ $badge }} fs-6 px-3 py-2">
            {{ ucfirst($tugas->status) }}
        </span>
    </div>

    {{-- Jadwal Pengiriman --}}
    <div class="card shadow-sm border-info mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-clock-history"></i>
                Waktu Pengantaran
            </h6>

            @if($tugas->pesanan->deliverySlot)
                <span class="badge bg-info fs-6">
                    {{ substr($tugas->pesanan->deliverySlot->waktu_mulai,0,5) }}
                    -
                    {{ substr($tugas->pesanan->deliverySlot->waktu_selesai,0,5) }}
                </span>
            @else
                <span class="text-muted">
                    Secepatnya
                </span>
            @endif
        </div>
    </div>

    {{-- Pembeli --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-person"></i>
                Nama Pembeli
            </h6>

            <div class="fw-semibold">
                {{ $tugas->pesanan->pengguna->username ?? '-' }}
            </div>
        </div>
    </div>

    {{-- Alamat --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-geo-alt"></i>
                Alamat Pengiriman
            </h6>

            @if(!empty($tugas->pesanan->alamat_pengiriman))
                <div>
                    {{ $tugas->pesanan->alamat_pengiriman }}
                </div>
                @if(!empty($tugas->pesanan->no_telp_pengiriman))
                    <small class="text-muted">
                        No. Telepon :
                        {{ $tugas->pesanan->no_telp_pengiriman }}
                    </small>
                @endif
            @else
                <span class="text-muted">

                    Alamat tidak tersedia.
                </span>
            @endif
        </div>
    </div>

    {{-- Produk --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-box-seam"></i>
                Produk Pesanan
            </h6>

            <ul class="list-group list-group-flush">
                @foreach($tugas->pesanan->detail as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            @if($item->type == 'produk')
                                {{ $item->produk->nama_produk }}
                                ({{ $item->size->size }})
                            @else
                                {{ $item->paket->nama_paket }}
                                <span class="badge bg-info ms-2">
                                    Paket
                                </span>
                            @endif
                        </div>

                        <span class="badge bg-secondary">
                            x{{ $item->quantity }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Total --}}
    <div class="card shadow-sm border-success mb-3">
        <div class="card-body text-center">
            <small class="text-muted">
                Total Pesanan
            </small>
            <h5 class="fw-bold text-success mb-0">
                Rp {{ number_format($tugas->pesanan->total,0,',','.') }}
            </h5>
        </div>
    </div>

    {{-- Bukti Pengiriman --}}
    @if($tugas->status == 'selesai' && $tugas->bukti_kirim)
    <div class="card shadow-sm border-success mb-3">
        <div class="card-body">
            <h6 class="fw-semibold text-success mb-3">
                <i class="bi bi-camera"></i>
                Bukti Pengiriman
            </h6>
            
            <img src="{{ asset('storage/'.$tugas->bukti_kirim) }}"
                 class="img-fluid rounded border mb-3"
                 style="max-height:300px;">

            <p>
                <strong>Waktu Kirim :</strong>
                {{ optional($tugas->waktu_kirim)->format('d M Y H:i') }}
            </p>

            @if($tugas->catatan_kurir)
                <p class="mb-0">
                    <strong>Catatan Kurir :</strong>
                    {{ $tugas->catatan_kurir }}
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Upload Bukti --}}
    @if($tugas->status == 'aktif')

    <div class="card shadow-sm mt-4">

        <div class="card-body">
            <h6 class="fw-semibold mb-3">
                <i class="bi bi-cloud-arrow-up"></i>
                Unggah Bukti Pengiriman
            </h6>

            <form method="POST"
                  action="{{ route('kurir.kirim',$tugas->id) }}"
                  enctype="multipart/form-data">

                @csrf
                <div class="mb-3">
                    <label class="form-label">
                        Foto Bukti Pengiriman
                    </label>
                    <input
                        type="file"
                        class="form-control"
                        name="bukti_kirim"
                        required>
                    <small class="text-muted">
                        Format JPG atau PNG, maksimal 2 MB.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Catatan Kurir (Opsional)
                    </label>
                    <textarea
                        name="catatan_kurir"
                        rows="3"
                        class="form-control"
                        placeholder="Contoh: Paket dititipkan kepada satpam."></textarea>
                </div>

                <button class="btn btn-success w-100">
                    <i class="bi bi-truck"></i>
                    Tandai Sudah Dikirim
                </button>
            </form>
        </div>
    </div>
    @endif
    {{-- Tombol kembali --}}
    <div class="mt-4">
        @if($tugas->status == 'aktif')
            <a href="{{ route('kurir.pesanan') }}"
               class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Pesanan Aktif
            </a>
        @else
            <a href="{{ route('kurir.riwayat') }}"
               class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Riwayat Pengiriman
            </a>
        @endif
    </div>
</div>
@endsection