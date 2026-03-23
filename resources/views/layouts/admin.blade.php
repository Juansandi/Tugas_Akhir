<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* SIDEBAR */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .brand-text {
            transition: opacity 0.2s ease;
        }

        /* saat collapse */
        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #212529;
            color: #fff;
            padding-top: 20px;
            transition: width 0.3s ease;
            overflow-x: hidden;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }

        .sidebar a i {
            font-size: 18px;
            min-width: 20px;
        }

        .sidebar a span {
            transition: all 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #343a40;
            color: #fff;
        }

        .submenu {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .submenu a {
            font-size: 14px;
            padding-left: 50px;
        }

        .content {
            margin-left: 240px;
            transition: margin-left 0.3s ease;
        }

        .topbar {
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        /* COLLAPSE */
        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed a span {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }

        .sidebar.collapsed .submenu {
            display: none;
        }

        .content.collapsed {
            margin-left: 70px;
        }
    </style>
</head>
<body>

@php
use App\Models\AdminNotification;

$notifs = AdminNotification::where('is_read', false)->latest()->get();

function isActive($routes) {
    foreach ((array)$routes as $route) {
        if (request()->routeIs($route)) return true;
    }
    return false;
}

$produkOpen = isActive(['products.*','admin.stok.*','admin.harga.*','admin.paket.*']);
$laporanOpen = isActive(['laporan.*','admin.laporan.*','admin.price_histories.*']);
@endphp

{{-- ================= SIDEBAR ================= --}}
<div class="sidebar">

    <div class="text-center mb-4 sidebar-brand">
        <i class="bi bi-shop fs-4"></i>
        <span class="brand-text ms-1">Admin Panel</span>
    </div>

    {{-- DASHBOARD --}}
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
    </a>

    {{-- PRODUK --}}
    <a data-bs-toggle="collapse" href="#menuProduk"
       class="{{ $produkOpen ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i>
        <span>Produk</span>
    </a>

    <div class="collapse submenu {{ $produkOpen ? 'show' : '' }}" id="menuProduk">
        <a href="{{ route('admin.products.index') }}"
           class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            Daftar Produk
        </a>
        <a href="{{ route('admin.stok.index') }}"
           class="{{ request()->routeIs('admin.stok.*') ? 'active' : '' }}">
            Stok Produk
        </a>
        <a href="{{ route('admin.harga.index') }}"
           class="{{ request()->routeIs('admin.harga.*') ? 'active' : '' }}">
            Harga Produk
        </a>
        <a href="{{ route('admin.paket.index') }}"
           class="{{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
            Paket
        </a>
    </div>

    {{-- PESANAN --}}
    <a href="{{ route('admin.pesanan.index') }}"
       class="{{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}">
        <i class="bi bi-cart-check"></i>
        <span>Pesanan</span>
    </a>

    {{-- PROMO --}}
    <a href="{{ route('admin.promos.index') }}"
       class="{{ request()->routeIs('admin.promos.*') ? 'active' : '' }}">
        <i class="bi bi-percent"></i>
        <span>Promo</span>
    </a>

    {{-- REFUND --}}
    <a href="{{ route('admin.refund.index') }}"
       class="{{ request()->routeIs('admin.refund.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-counterclockwise"></i>
        <span>Refund</span>
    </a>

    {{-- PENGGUNA --}}
    <a href="{{ route('admin.pengguna.index') }}"
       class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Pengguna</span>
    </a>

    {{-- LAPORAN --}}
    <a data-bs-toggle="collapse" href="#menuLaporan"
       class="{{ $laporanOpen ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow"></i>
        <span>Laporan</span>
    </a>

    <div class="collapse submenu {{ $laporanOpen ? 'show' : '' }}" id="menuLaporan">
        <a href="{{ route('admin.laporan.index') }}">Penjualan</a>
        <a href="{{ route('admin.laporan.detail') }}">Detail</a>
        <a href="{{ route('admin.laporan.refund') }}">Refund</a>
        <a href="{{ route('admin.laporan.produk_terlaris') }}">Produk</a>
        <a href="{{ route('admin.laporan.paket_terlaris') }}">Paket</a>
        <a href="{{ route('admin.price_histories.index') }}">Histori Harga</a>
    </div>

    {{-- SUPER ADMIN --}}
    @if(auth()->user()->role === 'super_admin')
        <a data-bs-toggle="collapse" href="#menuAkun">
            <i class="bi bi-shield-lock"></i>
            <span>Manajemen Akun</span>
        </a>

        <div class="collapse submenu" id="menuAkun">
            <a href="{{ route('admin.user.admin') }}">Admin</a>
            <a href="{{ route('admin.user.kurir') }}">Kurir</a>
        </div>
    @endif

</div>

{{-- ================= CONTENT ================= --}}
<div class="content">

    {{-- TOPBAR --}}
    <div class="topbar d-flex justify-content-between align-items-center px-4">

        <button id="toggleSidebar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list"></i>
        </button>

        <div class="d-flex align-items-center">

            {{-- NOTIF --}}
            <div class="dropdown me-3">
                {{-- ICON --}}
                <a data-bs-toggle="dropdown" class="position-relative text-dark" style="cursor:pointer;">
                    <i class="bi bi-bell fs-5"></i>

                    @if($notifs->count())
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                            {{ $notifs->count() }}
                        </span>
                    @endif
                </a>

                {{-- DROPDOWN --}}
                <ul class="dropdown-menu dropdown-menu-end p-0" style="width:300px">

                    {{-- HEADER --}}
                    <li class="px-3 py-2 border-bottom fw-semibold">
                        Notifikasi
                    </li>

                    {{-- LIST NOTIF --}}
                    @forelse($notifs as $notif)
                        <li>
                            <a class="dropdown-item py-2"
                            href="{{ route('admin.notifications.read',$notif->id) }}">

                                <div style="font-size:14px;">
                                    {{ $notif->pesan }}
                                </div>

                                <small class="text-muted">
                                    {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </a>
                        </li>
                    @empty
                        <li class="text-center py-3 text-muted">
                            Tidak ada notifikasi
                        </li>
                    @endforelse

                    <li>
                        <form action="{{ route('admin.notifications.readAll') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-center text-success">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    </li>
                    
                    {{-- FOOTER --}}
                    <li class="border-top text-center">
                        <a href="{{ route('admin.notifications.index') }}"
                        class="dropdown-item text-primary">
                            Lihat Semua Notifikasi
                        </a>
                    </li>

                </ul>
            </div>

            {{-- USER --}}
            <div class="dropdown">
                <a class="dropdown-toggle text-dark" data-bs-toggle="dropdown">
                    {{ auth()->user()->username }}
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item-text">
                        Role:
                        <span class="badge bg-{{ auth()->user()->role === 'super_admin' ? 'danger' : 'primary' }}">
                            {{ auth()->user()->role }}
                        </span>
                    </li>
                    <li><hr></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- CONTENT --}}
    <main class="p-4">
        @yield('content')
    </main>

</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const btn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');

    // load state
    if (localStorage.getItem('sidebar') === 'collapsed') {
        sidebar.classList.add('collapsed');
        content.classList.add('collapsed');
    }

    btn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');

        localStorage.setItem(
            'sidebar',
            sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded'
        );
    });
</script>
@yield('scripts')
</body>
</html>