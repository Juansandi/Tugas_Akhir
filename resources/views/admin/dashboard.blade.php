@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>

<div class="card mt-4">
    <div class="card-header">Pesanan Terbaru (Belum Selesai)</div>
        <div class="card-body">
            @if($pesananTerbaru->isEmpty())
                <p class="text-center">Tidak ada pesanan terbaru.</p>
            @else
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
                        @foreach($pesananTerbaru as $index => $pesanan)
                            @php
                                $status = strtolower($pesanan->status);
                                $badgeClass = match($status) {
                                    'menunggu konfirmasi' => 'bg-secondary text-light',
                                    'diproses'            => 'bg-primary text-light',
                                    'dikirim'             => 'bg-info text-dark',
                                    'diterima'            => 'bg-warning text-dark',
                                    'selesai'             => 'bg-success text-light',
                                    default               => 'bg-light text-dark'
                                };
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pesanan->pengguna->username ?? 'Guest' }}</td>
                                <td>
                                    @foreach($pesanan->detail as $detail)
                                        {{ $detail->produk->nama_produk }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ ucfirst($pesanan->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    </br>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="card-text fs-4">{{ $totalProduk }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success shadow h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Pesanan Hari Ini</h5>
                    <p class="card-text fs-4">{{ $totalPesanan }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan Bulan Ini</h5>
                    <p class="card-text fs-4">Rp {{ number_format($totalPenjualanBulanIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Grafik Penjualan Bulanan</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>

</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- import Chart.js -->

<script>
    const ctx = document.getElementById('salesChart').getContext('2d'); // pastikan getContext('2d')
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: @json($salesDataFull),
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.8)',  // opacity 0.8, lebih pekat
                borderColor: '#0a58ca',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Total Penjualan per Bulan' }
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
