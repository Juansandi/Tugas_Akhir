@extends('layouts.admin')

@section('title','Laporan Refund')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Laporan Pengembalian Dana</h4>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Dari</label>
            <input type="date" name="start_date" class="form-control"
                value="{{ request('start_date') }}">
        </div>
        <div class="col-md-4">
            <label>Sampai</label>
            <input type="date" name="end_date" class="form-control"
                value="{{ request('end_date') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('admin.laporan.refund') }}"
            class="btn btn-secondary w-100">Atur Ulang</a>
        </div>
    </form>

    <a href="{{ route('admin.laporan.refund.pdf', request()->query()) }}"
    class="btn btn-danger mb-3">
    🧾 Unduh PDF
    </a>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6>Total Pengembalian Dana</h6>
            <h4 class="fw-bold text-danger">
                Rp {{ number_format($totalRefund,0,',','.') }}
            </h4>
            <small class="text-muted">
                {{ $startDate->format('d M Y') }} –
                {{ $endDate->format('d M Y') }}
            </small>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>ID Pengembalian</th>
                    <th>Pesanan</th>
                    <th>Pembeli</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $r)
                    <tr>
                        <td>#{{ $r->id }}</td>
                        <td>#{{ $r->pesanan->id }}</td>
                        <td>{{ $r->pengguna->username }}</td>
                        <td class="text-danger fw-semibold">
                        Rp {{ number_format($r->refund_amount,0,',','.') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->approved_at)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada data pengembalian dana.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
