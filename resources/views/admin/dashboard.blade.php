@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>

    <div class="card mt-4">
        <div class="card-header">Pesanan Terbaru</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pembeli</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Dina</td>
                        <td>Beras Merah</td>
                        <td>Rp 50.000</td>
                        <td><span class="badge bg-success">Selesai</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </br>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Grafik Penjualan Bulanan</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');

    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: [50000, 70000, 80000, 65000, 90000, 110000, 95000, 120000, 105000, 130000, 100000, 125000],
                fill: true,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Total Penjualan per Bulan (Dummy)'
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
</script>
@endsection
