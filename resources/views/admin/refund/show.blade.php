@extends('layouts.admin')

@section('title', 'Detail Refund')

@section('content')
<div class="container py-4">
    <h4>Detail Pengajuan Refund</h4>

    <div class="card mb-3">
        <div class="card-body">
            @if($refund->bukti_foto)
                <p><strong>Bukti Foto:</strong></p>
                <img src="{{ asset('storage/' . $refund->bukti_foto) }}" width="300" class="img-thumbnail">
            @endif
            <p><strong>ID Pesanan:</strong> #{{ $refund->pesanan->id }}</p>
            <p><strong>Customer:</strong> {{ $refund->pengguna->username }}</p>
            <p><strong>Alasan Refund:</strong> {{ $refund->alasan }}</p>
            <p><strong>Metode Pengembalian:</strong> {{ $refund->metode_refund }}</p>
            <p><strong>Nomor Rekening / Nomor E-Wallet:</strong> {{ $refund->nomor_tujuan }}</p>
            @if($refund->bukti)
                <p><strong>Bukti Pendukung:</strong></p>
                <img src="{{ asset('storage/' . $refund->bukti) }}" width="300" class="img-thumbnail">
            @endif

            @php
                $badgeClass = match($refund->status) {
                    'diajukan'  => 'bg-warning text-dark',
                    'disetujui' => 'bg-success text-white',
                    'ditolak'   => 'bg-danger text-white',
                    default     => 'bg-secondary text-white'
                };
            @endphp

            <p><strong>Status:</strong>
                <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($refund->status) }}
                </span>
            </p>

            @if ($refund->status == 'diajukan')
                <form action="{{ route('admin.refund.update', $refund->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="respon_admin" class="form-label">Respon Admin</label>
                        <textarea name="respon_admin" id="respon_admin" rows="3" class="form-control" required></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" name="action" value="approve" class="btn btn-success">
                            Setujui Refund
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger">
                            Tolak Refund
                        </button>
                    </div>
                </form>
            @else
                <p><strong>Respon Admin:</strong> {{ $refund->respon_admin }}</p>
            @endif
        </div>
    </div>

    <a href="{{ route('refund.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
@endsection
