<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-nav .nav-link.active {
            font-weight: 600;
            background-color: rgba(13,110,253,.1);
            border-radius: 6px;
        }
        .dropdown-item.active {
            font-weight: 600;
            background-color: rgba(13,110,253,.1);
        }
    </style>
</head>
<body>

@php
    use App\Models\AdminNotification;
    $notifs = AdminNotification::where('is_read', false)->latest()->get();
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

        {{-- BRAND --}}
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            <img src="https://cdn-icons-png.flaticon.com/512/591/591788.png" width="30" class="me-2">
            Admin Panel
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">

            {{-- ================= MENU KIRI ================= --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- PRODUK --}}
                @php
                    $produkActive =
                        request()->routeIs('products.*') ||
                        request()->routeIs('admin.stok.*') ||
                        request()->routeIs('admin.harga.*') ||
                        request()->routeIs('admin.paket.*');
                @endphp

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $produkActive ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}"
                               href="{{ route('products.index') }}">
                                Daftar Produk
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.stok.*') ? 'active' : '' }}"
                               href="{{ route('admin.stok.index') }}">
                                Stok Produk
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.harga.*') ? 'active' : '' }}"
                               href="{{ route('admin.harga.index') }}">
                                Harga Produk
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}"
                               href="{{ route('admin.paket.index') }}">
                                Paket
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- PESANAN --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}"
                       href="{{ route('pesanan.index') }}">
                        <i class="bi bi-cart-check me-1"></i> Pesanan
                    </a>
                </li>

                {{-- PROMO --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promos.*') ? 'active' : '' }}"
                       href="{{ route('promos.index') }}">
                        <i class="bi bi-percent me-1"></i> Promo
                    </a>
                </li>

                {{-- REFUND --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('refund.*') ? 'active' : '' }}"
                       href="{{ route('refund.index') }}">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Refund
                    </a>
                </li>

                {{-- PENGGUNA --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"
                       href="{{ route('pengguna.index') }}">
                        <i class="bi bi-people me-1"></i> Pengguna
                    </a>
                </li>

                {{-- LAPORAN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin/laporan*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-graph-up-arrow me-1"></i> Laporan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('laporan.index') }}">Laporan Penjualan</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.laporan.detail') }}">Detail Penjualan</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.laporan.produk_terlaris') }}">Produk Terlaris</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.laporan.paket_terlaris') }}">Paket Terlaris</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.price_histories.index') }}">Histori Harga</a></li>
                    </ul>
                </li>

                {{-- MANAJEMEN AKUN --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin/akun/*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-lock me-1"></i> Manajemen Akun
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.user.admin') }}">Admin</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.user.kurir') }}">Pegawai / Kurir</a></li>
                    </ul>
                </li>
            </ul>

            {{-- ================= MENU KANAN ================= --}}
            <ul class="navbar-nav ms-auto">

                {{-- NOTIFIKASI --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @if($notifs->count())
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $notifs->count() }}
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width:300px; max-height:400px; overflow:auto">
                        @forelse($notifs as $notif)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.notifications.read', $notif->id) }}">
                                    {{ \Illuminate\Support\Str::limit($notif->pesan, 60) }}
                                    <br>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @empty
                            <li><span class="dropdown-item text-center">Tidak ada notifikasi</span></li>
                        @endforelse
                        <li>
                            <a href="{{ route('admin.notifications.index') }}"
                               class="dropdown-item text-center fw-semibold">
                                Lihat Semua
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- USER --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        {{ auth()->user()->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text text-muted">
                                Role: {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

{{-- ================= KONTEN ================= --}}
<main class="py-4">
    @yield('content')
</main>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

</body>
</html>
