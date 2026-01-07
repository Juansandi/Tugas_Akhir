@extends('layouts.admin')

@section('title', 'Laporan Paket Terlaris')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Laporan Paket Terlaris</h2>

    {{-- FILTER --}}
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <label>Dari Tanggal</label>
            <input type="date" name="start_date"
                   class="form-control"
                   value="{{ request('start_date') }}">
        </div>
        <div class="col-md-4">
            <label>Sampai Tanggal</label>
            <input type="date" name="end_date"
                   class="form-control"
                   value="{{ request('end_date') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('admin.laporan.paket_terlaris') }}"
               class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    {{-- DOWNLOAD PDF --}}
    <div class="mb-3">
        <a href="{{ route('admin.laporan.paket_terlaris_pdf', request()->query()) }}"
           class="btn btn-danger">
            🧾 Download PDF
        </a>
    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Total Terjual</th>
                        <th>Total Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paketTerlaris as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_paket }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>
                                Rp {{ number_format($item->total_omzet, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada data paket
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
