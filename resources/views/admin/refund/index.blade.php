@extends('layouts.admin')

@section('title', 'Daftar Refund')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Daftar Pengajuan Refund</h4>

    @if($refunds->isEmpty())
        <div class="alert alert-info">
            Belum ada pengajuan refund.
        </div>
    @else
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th width="90">Refund</th>
                    <th width="100">Pesanan</th>
                    <th>Customer</th>
                    <th>Alasan</th>
                    <th width="140">Nominal</th>
                    <th width="120">Status</th>
                    <th width="150">Waktu</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($refunds as $refund)
                @php
                    $badgeClass = match($refund->status) {
                        'diajukan'  => 'bg-warning text-dark',
                        'disetujui' => 'bg-success',
                        'ditolak'   => 'bg-danger',
                        default     => 'bg-secondary'
                    };
                @endphp
                <tr>
                    <td class="text-center fw-semibold">#{{ $refund->id }}</td>
                    <td class="text-center">#{{ $refund->pesanan->id }}</td>
                    <td>{{ $refund->pengguna->username }}</td>
                    <td>{{ Str::limit($refund->alasan, 60) }}</td>

                    <td class="text-end text-danger fw-semibold">
                        {{ $refund->refund_amount
                            ? 'Rp '.number_format($refund->refund_amount,0,',','.')
                            : '-' }}
                    </td>

                    <td class="text-center">
                        <span class="badge {{ $badgeClass }} px-3 py-2">
                            {{ ucfirst($refund->status) }}
                        </span>
                    </td>

                    <td class="text-center text-muted">
                        {{ $refund->created_at->format('d M Y') }}<br>
                        <small>{{ $refund->created_at->format('H:i') }}</small>
                    </td>

                    <td class="text-center">
                        <a href="{{ route('admin.refund.show', $refund->id) }}"
                           class="btn btn-sm btn-outline-primary w-100">
                            Detail
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
