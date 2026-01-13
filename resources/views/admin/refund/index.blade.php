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
                    <th width="120">Refund</th>
                    <th width="120">Pesanan</th>
                    <th width="180">Customer</th>
                    <th>Alasan</th>
                    <th width="120">Status</th>
                    <th width="160">Waktu</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>

            @foreach($refunds as $refund)
                @php
                    $badgeClass = match($refund->status) {
                        'diajukan'  => 'bg-warning text-dark',
                        'disetujui' => 'bg-success text-white',
                        'ditolak'   => 'bg-danger text-white',
                        default     => 'bg-secondary text-white'
                    };
                @endphp

                <tr>
                    {{-- REFUND --}}
                    <td class="text-center fw-semibold">
                        #{{ $refund->id }}
                    </td>

                    {{-- PESANAN --}}
                    <td class="text-center">
                        #{{ $refund->pesanan->id }}
                    </td>

                    {{-- CUSTOMER --}}
                    <td class="text-center">
                        {{ $refund->pengguna->username }}
                    </td>

                    {{-- ALASAN --}}
                    <td class="text-center">
                        {{ Str::limit($refund->alasan, 60) }}
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }} px-3 py-2">
                            {{ ucfirst($refund->status) }}
                        </span>
                    </td>

                    {{-- WAKTU --}}
                    <td class="text-center text-muted">
                        {{ $refund->created_at->format('d M Y') }}<br>
                        <small>{{ $refund->created_at->format('H:i') }}</small>
                    </td>

                    {{-- AKSI --}}
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
