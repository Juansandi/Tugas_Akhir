@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Laporan Penjualan</h2>

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
            <a href="{{ route('laporan.index') }}"
               class="btn btn-secondary w-100">
                Reset
            </a>
        </div>
    </form>

    {{-- TOMBOL DOWNLOAD PDF --}}
    <div class="mb-4">
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
           class="btn btn-danger">
            🧾 Download PDF
        </a>
    </div>

    {{-- RINGKASAN --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6>Total Penjualan</h6>
                <h4 class="fw-bold text-success">
                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                </h4>
                <small class="text-muted">
                    Periode:
                    {{ $startDate->format('d M Y') }}
                    –
                    {{ $endDate->format('d M Y') }}
                </small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h6>Jumlah Transaksi</h6>
                <h4 class="fw-bold">
                    {{ $jumlahTransaksi }} Transaksi
                </h4>
            </div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="card shadow-sm p-4">
        <h5 class="mb-3">Grafik Penjualan Berdasarkan Kategori</h5>

        @if($penjualanKategori->isEmpty())
            <p class="text-muted mb-0">
                Tidak ada data penjualan pada periode ini.
            </p>
        @else
            <canvas id="kategoriChart" height="120"></canvas>
        @endif
    </div>

</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(!$penjualanKategori->isEmpty())
    const ctx = document.getElementById('kategoriChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($penjualanKategori->pluck('nama_kategori')) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($penjualanKategori->pluck('total_penjualan')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
@endif
</script>
@endsection
