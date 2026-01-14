@extends('layouts.admin')

@section('title', 'Detail Refund')

@section('content')
<div class="container py-4" style="max-width:900px">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Detail Pengajuan Refund</h4>
        <a href="{{ route('refund.index') }}" class="btn btn-outline-secondary btn-sm">
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
                             style="max-height:300px; object-fit:contain;">
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

                <form action="{{ route('admin.refund.update', $refund->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Refund</label>
                        <input type="number"
                               name="refund_amount"
                               class="form-control"
                               min="0"
                               max="{{ $refund->pesanan->total }}"
                               required>
                        <small class="text-muted">
                            Maksimal Rp {{ number_format($refund->pesanan->total,0,',','.') }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Respon Admin</label>
                        <textarea name="respon_admin"
                                  rows="3"
                                  class="form-control"
                                  required></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit"
                                name="action"
                                value="approve"
                                class="btn btn-success w-50">
                            Setujui Refund
                        </button>
                        <button type="submit"
                                name="action"
                                value="reject"
                                class="btn btn-danger w-50">
                            Tolak Refund
                        </button>
                    </div>
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
@endsection
