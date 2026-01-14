 @extends('layouts.admin')

@section('title', 'Laporan Detail Penjualan')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Laporan Detail Penjualan</h2>
    <small class="text-muted">
        *Nilai penjualan belum dikurangi refund
    </small>

    {{-- FILTER TANGGAL --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Dari Tanggal</label>
            <input type="date"
                   name="start_date"
                   class="form-control"
                   value="{{ request('start_date') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date"
                   name="end_date"
                   class="form-control"
                   value="{{ request('end_date') }}">
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-100">
                Filter
            </button>
            <a href="{{ route('admin.laporan.detail') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>
    
    {{-- TOMBOL DOWNLOAD PDF --}}
    <a href="{{ route('admin.laporan.detail.pdf', request()->query()) }}"
        class="btn btn-danger mb-3">
            🧾 Download PDF
    </a>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Item</th>
                        <th>Jenis</th>
                        <th>Qty</th>
                        <th>Harga (Rp)</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailPenjualan as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>#{{ $row->pesanan_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $row->nama_item }}</td>
                            <td>{{ ucfirst($row->type) }}</td>
                            <td>{{ $row->quantity }}</td>
                            <td>{{ number_format($row->price, 0, ',', '.') }}</td>
                            <td>{{ number_format($row->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <small class="text-muted">
                    Periode:
                    {{ $startDate->format('d M Y') }}
                    –
                    {{ $endDate->format('d M Y') }}
                </small>
            </table>

        </div>
    </div>

</div>
@endsection
