@extends('layouts.admin')

@section('title', 'Detail Refund')

@section('content')
<div class="container py-4" style="max-width:900px">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Detail Pengajuan Refund</h4>
        <a href="{{ route('admin.refund.index') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    @php
        $badgeClass = match($refund->status) {
            'diajukan'  => 'bg-warning text-dark',
            'disetujui' => 'bg-success',
            'ditolak'   => 'bg-danger',
            default     => 'bg-secondary'
        };
    @endphp

    {{-- STATUS --}}
    <div class="mb-3">
        <span class="badge {{ $badgeClass }} px-3 py-2 fs-6">
            {{ strtoupper($refund->status) }}
        </span>
    </div>

    <div class="row g-4">

        {{-- INFO --}}
        <div class="col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <h6 class="fw-bold mb-3">Informasi Refund</h6>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">ID Refund</td>
                            <td class="fw-semibold">#{{ $refund->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pesanan</td>
                            <td>
                                #{{ $refund->pesanan->id }}
                                <a href="{{ route('admin.pesanan.show', $refund->pesanan->id) }}"
                                   class="ms-2 small">
                                    (Lihat Pesanan)
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Customer</td>
                            <td>{{ $refund->pengguna->username }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alasan</td>
                            <td>{{ $refund->alasan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode Refund</td>
                            <td>{{ $refund->metode_refund }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tujuan</td>
                            <td>{{ $refund->nomor_tujuan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nominal Refund</td>
                            <td class="fw-bold text-danger">
                                Rp {{ number_format($refund->refund_amount ?? 0,0,',','.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Diajukan</td>
                            <td>{{ $refund->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @if($refund->approved_at)
                        <tr>
                            <td class="text-muted">Diproses</td>
                            <td>{{ $refund->approved_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>

                </div>
            </div>
        </div>

        {{-- BUKTI --}}
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Bukti Pendukung</h6>

                    @if($refund->bukti_foto)
                        <img src="{{ asset('storage/'.$refund->bukti_foto) }}"
                            class="img-fluid rounded border"
                            style="max-height:300px; object-fit:contain; cursor:pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#modalFotoRefund">

                    @else
                        <p class="text-muted mb-0">Tidak ada bukti foto.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION ADMIN --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">

            @if ($refund->status === 'diajukan')
                <h6 class="fw-bold mb-3">Tindakan Admin</h6>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.refund.update', $refund->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- TAMPILKAN ERROR (WAJIB) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- KEPUTUSAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keputusan Admin</label>

                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="keputusan"
                                id="approve"
                                value="approve"
                                required>
                            <label class="form-check-label text-success fw-semibold" for="approve">
                                Setujui Refund
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                type="radio"
                                name="keputusan"
                                id="reject"
                                value="reject"
                                required>
                            <label class="form-check-label text-danger fw-semibold" for="reject">
                                Tolak Refund
                            </label>
                        </div>
                    </div>

                    {{-- NOMINAL (SELALU ADA) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Refund</label>
                        <input type="number"
                            name="refund_amount"
                            class="form-control"
                            value="0"
                            min="0"
                            max="{{ $refund->pesanan->total }}"
                            required>
                        <small class="text-muted">
                            Isi <strong>0</strong> jika refund ditolak
                        </small>
                    </div>

                    {{-- RESPON ADMIN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Respon Admin</label>
                        <textarea name="respon_admin"
                                rows="3"
                                class="form-control"
                                required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Simpan Keputusan
                    </button>
                </form>
            @else
                <div class="alert
                    {{ $refund->status === 'disetujui'
                        ? 'alert-success'
                        : 'alert-danger' }}">
                    <strong>Respon Admin:</strong><br>
                    {{ $refund->respon_admin }}
                </div>
            @endif

        </div>
    </div>

</div>
@if($refund->bukti_foto)
<div class="modal fade" id="modalFotoRefund" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">

            <div class="text-end">
                <button type="button"
                        class="btn-close bg-white p-2 m-2"
                        data-bs-dismiss="modal"></button>
            </div>

            <img src="{{ asset('storage/'.$refund->bukti_foto) }}"
                 class="img-fluid rounded shadow">
        </div>
    </div>
</div>
@endif
@endsection
