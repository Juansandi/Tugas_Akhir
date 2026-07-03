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
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #198754;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
        }

        .sidebar h5 {
            color: #fff;
            font-weight: 700;
            letter-spacing: .3px;
        }
        /* ===========================
        Profil
        =========================== */
        .profile-box {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 10px;
            padding: 12px;
            color: #fff;
        }

        .profile-box strong{
            font-size: 1rem;
        }

        .profile-box small{
            opacity: .85;
        }

        /* ===========================
        Menu
        =========================== */
        .sidebar .nav-link{
            color:#fff;
            border-radius:8px;
            padding:10px 14px;
            transition:all .2s ease;
            display:flex;
            align-items:center;
            gap:10px;
            font-weight:500;
        }

        /* Hover */
        .sidebar .nav-link:hover{
            background:rgba(255,255,255,.10);
            color:#fff;
        }

        /* Menu aktif */
        .sidebar .nav-link.active{
            background:rgba(255,255,255,.18);
            color:#fff;
            font-weight:600;
        }

        /* Ikon menu */
        .sidebar .nav-link i{
            width:20px;
            text-align:center;
        }

        /* ===========================
        Garis sebelum logout
        =========================== */
        .sidebar hr{
            border-color:rgba(255,255,255,.25);
        }

        /* ===========================
        Tombol Logout
        =========================== */
        .logout-btn{
            color:#ffb3b3 !important;
        }

        .logout-btn:hover{
            background:rgba(255,255,255,.15);
            color:#fff !important;
        }

        /* ===========================
        Area Content
        =========================== */
        .content-area{
            flex:1;
            padding:30px;
        }
    </style>
</head>
<body>

<div class="d-flex">

    {{-- SIDEBAR --}}
    <div class="sidebar p-3">
        <h5 class="mb-4">
            <i class="bi bi-truck"></i> Panel Kurir
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
            <li class="mt-3">
                <hr>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="nav-link btn btn-link text-start logout-btn">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>

    {{-- CONTENT --}}
    <div class="content-area">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
