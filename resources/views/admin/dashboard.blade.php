@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">Beranda Admin</h2>

    {{-- ===================== --}}
    {{-- ZONA 1 : STATISTIK --}}
    {{-- ===================== --}}
    <div class="row g-3 mb-4">
        {{-- PRODUK --}}
        <div class="col-md-4">
            <div class="card border-primary shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-2 text-primary mb-2"></i>
                    <h6 class="text-primary mb-1">Total Produk</h6>
                    <h4 class="fw-bold text-primary">{{ $totalProduk }}</h4>
                </div>
            </div>
        </div>

        {{-- PESANAN MASUK --}}
        <div class="col-md-4">
            <div class="card border-info shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cart-plus fs-2 text-info mb-2"></i>
                    <h6 class="text-info mb-1">Pesanan Masuk Hari Ini</h6>
                    <h4 class="fw-bold text-info">{{ $pesananMasukHariIni }}</h4>
                </div>
            </div>
        </div>

        {{-- PESANAN AKTIF --}}
        <div class="col-md-4">
            <div class="card border-warning shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-hourglass-split fs-2 text-warning mb-2"></i>
                    <h6 class="text-warning mb-1">Pesanan Aktif</h6>
                    <h4 class="fw-bold text-warning">{{ $pesananAktifHariIni }}</h4>
                </div>
            </div>
        </div>

        {{-- PENJUALAN KOTOR --}}
        <div class="col-md-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack fs-2 text-success mb-2"></i>
                    <h6 class="text-success mb-1">Total Penjualan Kotor</h6>
                    <h4 class="fw-bold text-success">Rp {{ number_format($totalPenjualanKotor,0,',','.') }}</h4>
                </div>
            </div>
        </div>

        {{-- REFUND --}}
        <div class="col-md-4">
            <div class="card border-danger shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-counterclockwise fs-2 text-danger mb-2"></i>
                    <h6 class="text-danger mb-1">Pengembalian Dana Bulan Ini</h6>
                    <h4 class="fw-bold text-danger">- Rp {{ number_format($totalRefundBulanIni,0,',','.') }}</h4>
                </div>
            </div>
        </div>

        {{-- PENJUALAN BERSIH --}}
        <div class="col-md-4">
            <div class="card border-dark shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-wallet2 fs-2 text-dark mb-2"></i>
                    <h6 class="text-dark mb-1">Total Penjualan Bersih</h6>
                    <h4 class="fw-bold text-dark">Rp {{ number_format($totalPenjualanBulanIni,0,',','.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- ZONA 2 : PESANAN & PRODUK --}}
    {{-- ===================== --}}
    <div class="row mb-4">

        {{-- PESANAN TERBARU --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-semibold">Pesanan Terbaru</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $i => $pesanan)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $pesanan->pengguna->username ?? 'Guest' }}</td>
                                <td>Rp {{ number_format($pesanan->total,0,',','.') }}</td>
                                <td>
                                    <span class="badge {{ $pesanan->status_badge }}">
                                        {{ $pesanan->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- ZONA 3 : GRAFIK --}}
    {{-- ===================== --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Grafik Penjualan Bulanan</h6>
            <canvas id="salesChart" height="120"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Penjualan Bersih',
                data: @json($salesDataFull),
                backgroundColor: 'rgba(25, 135, 84, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
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
