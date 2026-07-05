@extends('layouts.admin')

@section('title', 'Laporan Produk Terlaris')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Laporan Produk Terlaris</h2>

    {{-- FILTER TANGGAL --}}
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date"
                   class="form-control"
                   value="{{ request('start_date') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date"
                   class="form-control"
                   value="{{ request('end_date') }}">
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
            <button class="btn btn-primary w-100">Filter</button>
            <a href="{{ route('admin.laporan.produk_terlaris') }}"
               class="btn btn-secondary w-100">Atur Ulang</a>
        </div>
    </form>

    {{-- DOWNLOAD PDF --}}
    <div class="mb-3">
        <a href="{{ route('admin.laporan.produk_terlaris_pdf', request()->query()) }}"
           class="btn btn-danger">
            🧾 Unduh PDF
        </a>
    </div>

    {{-- TABEL PRODUK TERLARIS --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Kategori</th>
                        <th>Total Terjual</th>
                        <th>Total Omzet</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produkTerlaris as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->ukuran }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>{{ $item->total_qty }}</td>
                            <td>
                                Rp {{ number_format($item->total_omzet, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data produk terlaris
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRAFIK --}}
    @if($grafikProdukTerlaris->isNotEmpty())
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-3">Top 5 Produk Terlaris (per Ukuran)</h5>
            <canvas id="produkTerlarisChart" height="120"></canvas>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($grafikProdukTerlaris->isNotEmpty())
<script>
    const dataProduk = @json($grafikProdukTerlaris);

    const ctx = document.getElementById('produkTerlarisChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataProduk.map(item => item.label),
                datasets: [{
                    label: 'Total Terjual',
                    data: dataProduk.map(item => item.total_qty),
                    backgroundColor: 'rgba(24, 176, 30, 0.8)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>
@endif
@endsection
