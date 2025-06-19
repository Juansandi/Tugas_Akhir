@extends('layouts.admin')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Laporan Penjualan</h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5>Penjualan Hari Ini</h5>
                <p>Rp{{ number_format($harian, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5>Penjualan Minggu Ini</h5>
                <p>Rp{{ number_format($mingguan, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5>Penjualan Bulan Ini</h5>
                <p>Rp{{ number_format($bulanan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h5>Grafik Penjualan Berdasarkan Kategori</h5>
        <canvas id="kategoriChart" width="400" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('kategoriChart').getContext('2d');
    const kategoriChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($penjualanKategori->pluck('kategori')) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($penjualanKategori->pluck('total_penjualan')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
