@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>

    {{-- Statistik Singkat --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-2 mb-2"></i>
                    <h6>Total Produk</h6>
                    <h4>{{ $totalProduk }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cart-check fs-2 mb-2"></i>
                    <h6>Pesanan Hari Ini</h6>
                    <h4>{{ $totalPesanan }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-coin fs-2 mb-2"></i>
                    <h6>Penjualan Bulan Ini</h6>
                    <h4>Rp {{ number_format($totalPenjualanBulanIni, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        @if($produkTerlaris && $produkTerlaris->produk)
        <div class="col-md-3 mb-3">
            <div class="card bg-warning shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-star-fill fs-2 mb-2"></i>
                    <h6>Produk Terlaris</h6>
                    <p class="mb-0 fw-bold">{{ $produkTerlaris->produk->nama_produk }}</p>
                    <small>Total Dipesan: {{ $produkTerlaris->total_terjual }}</small>
                </div>
            </div>
            <a href="{{ route('admin.laporan.produk_terlaris') }}" class="small text-dark">
                Lihat laporan →
            </a>
        </div>
        @endif
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>Pesanan Terbaru (Belum Selesai)</strong>
        </div>
        <div class="card-body">
            @if($pesananTerbaru->isEmpty())
                <p class="text-center text-muted">Tidak ada pesanan terbaru.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
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
                                            @if($detail->type === 'produk' && $detail->produk)
                                                {{ $detail->produk->nama_produk }}
                                            @elseif($detail->type === 'paket' && $detail->paket)
                                                {{ $detail->paket->nama_paket }} (Paket)
                                            @endif
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                                    <td><span class="badge {{ $badgeClass }}">{{ ucfirst($pesanan->status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Grafik Penjualan --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Grafik Penjualan Bulanan</h5>
            <canvas id="salesChart" height="100"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap Icons (opsional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: @json($salesDataFull),
                backgroundColor: 'rgba(13, 110, 253, 0.8)',
                borderColor: '#0a58ca',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
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
