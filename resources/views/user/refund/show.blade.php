@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:720px">

    <h4 class="fw-bold mb-3">
        Detail Pengembalian Dana
        <span class="text-muted">Pesanan #{{ $refund->pesanan->id }}</span>
    </h4>

    @php
        $badgeClass = match($refund->status) {
            'diajukan'  => 'bg-warning text-dark',
            'disetujui' => 'bg-success text-white',
            'ditolak'   => 'bg-danger text-white',
            default     => 'bg-secondary text-white'
        };
    @endphp

    <div class="card shadow-sm mb-3">
        <div class="card-body">

            {{-- STATUS --}}
            <div class="mb-3">
                <span class="badge {{ $badgeClass }} px-3 py-2">
                    {{ ucfirst($refund->status) }}
                </span>
            </div>

            {{-- INFO REFUND --}}
            <table class="table table-borderless mb-0">
                <tr>
                    <td width="180" class="text-muted">Alasan Pengembalian Dana</td>
                    <td class="fw-semibold">{{ $refund->alasan }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Metode Pengembalian Dana</td>
                    <td>{{ $refund->metode_refund }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tujuan Pengembalian Dana</td>
                    <td>{{ $refund->nomor_tujuan }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Pengajuan</td>
                    <td>{{ $refund->created_at->format('d M Y, H:i') }}</td>
                </tr>
            </table>

            {{-- BUKTI FOTO --}}
            @if ($refund->bukti_foto)
                <hr>
                <p class="fw-semibold mb-2">Bukti Pendukung</p>
                <img src="{{ asset('storage/' . $refund->bukti_foto) }}"
                     alt="Bukti Refund"
                     class="img-fluid rounded border"
                     style="max-height:300px; object-fit:contain;">
            @endif

            {{-- RESPON ADMIN --}}
            @if ($refund->respon_admin)
                <hr>
                <div class="alert
                    {{ $refund->status === 'disetujui'
                        ? 'alert-success'
                        : 'alert-danger' }}">
                    <strong>Respon Admin:</strong><br>
                    {{ $refund->respon_admin }}
                </div>
            @endif
            @if($refund->status === 'disetujui' && $refund->refund_amount)
                <tr>
                    <td class="text-muted">Nominal Refund</td>
                    <td class="fw-bold text-success">
                        Rp {{ number_format($refund->refund_amount,0,',','.') }}
                    </td>
                </tr>
            @endif

        </div>
    </div>

    {{-- FOOTER --}}
    <div class="d-flex gap-2">
        <a href="{{ route('pesanan.history') }}"
           class="btn btn-outline-secondary w-100">
            ← Kembali ke Riwayat Pesanan
        </a>
    </div>

</div>
@endsection
