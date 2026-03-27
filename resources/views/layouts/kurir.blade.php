<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kurir Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #198754;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar a.active, .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }
        .profile-box {
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 10px;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    <div class="sidebar p-3">
        <h5 class="text-white mb-4">
            <i class="bi bi-truck"></i> Kurir Panel
        </h5>

        {{-- Profile --}}
        <div class="profile-box mb-4">
            <strong>{{ auth()->user()->username }}</strong><br>
            <small>Kurir</small>
        </div>

        {{-- Menu --}}
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kurir.dashboard') ? 'active' : '' }}"
                   href="{{ route('kurir.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Beranda
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kurir.pesanan') ? 'active' : '' }}"
                   href="{{ route('kurir.pesanan') }}">
                    <i class="bi bi-box-seam"></i> Pesanan
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kurir.profil') }}">
                    <i class="bi bi-person"></i> Profil
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kurir.riwayat') ? 'active' : '' }}"
                href="{{ route('kurir.riwayat') }}">
                    <i class="bi bi-clock-history"></i> Riwayat
                </a>
            </li>

            <li class="nav-item mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="nav-link btn btn-link text-start text-danger">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>

    {{-- CONTENT --}}
    <div class="flex-fill p-4">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
