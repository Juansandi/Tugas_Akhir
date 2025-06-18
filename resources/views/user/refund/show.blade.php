@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Detail Refund Pesanan #{{ $refund->pesanan->id }}</h4>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Alasan Refund:</strong> {{ $refund->alasan }}</p>
            <p><strong>Metode Pengembalian:</strong> {{ $refund->metode_refund }}</p>
            <p><strong>Nomor Rekening / E-Wallet:</strong> {{ $refund->nomor_tujuan }}</p>

            @if ($refund->bukti_foto)
                <p><strong>Bukti Foto:</strong></p>
                <img src="{{ asset('storage/' . $refund->bukti_foto) }}" alt="Bukti Foto" class="img-thumbnail" width="300">
            @endif

             @php
                $badgeClass = match($refund->status) {
                    'diajukan'  => 'bg-warning text-dark',
                    'disetujui' => 'bg-success text-white',
                    'ditolak'   => 'bg-danger text-white',
                    default     => 'bg-secondary text-white'
                };
            @endphp

            <p><strong>Status Refund:</strong>
                <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($refund->status) }}
                </span>
            </p>

            @if ($refund->respon_admin)
                <p><strong>Respon Admin:</strong> {{ $refund->respon_admin }}</p>
            @endif

            <p><strong>Tanggal Pengajuan:</strong> {{ $refund->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('pesanan.history') }}" class="btn btn-secondary">← Kembali ke Riwayat Pesanan</a>
</div>
@endsection
