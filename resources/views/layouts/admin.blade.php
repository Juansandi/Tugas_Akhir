<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-link.active {
            font-weight: bold;
            text-decoration: underline;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    @php
    use App\Models\AdminNotification;
    // Ambil notifikasi belum dibaca, urut terbaru
    $notifs = AdminNotification::where('is_read', false)->latest()->get();
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}"> 
            <img src="https://cdn-icons-png.flaticon.com/512/591/591788.png" width="30" class="me-2">
            Admin Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promos.*') ? 'active' : '' }}" href="{{ route('promos.index') }}">Promo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('refund.*') ? 'active' : '' }}" href="{{ route('refund.index') }}">Refund</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">Pengguna</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">

                {{-- Notifikasi Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if($notifs->count())
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $notifs->count() }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        @forelse ($notifs as $notif)
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

                        @if($notifs->count())
                            <li class="px-3 py-1">
                                <form action="{{ route('admin.notifications.readAll') }}" method="POST" class="text-center">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link">Tandai Semua Dibaca</button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li class="px-3 py-2 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-list"></i> Lihat Semua
                            </a>
                        </li>
                    </ul>
                </li>


                {{-- User Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('laporan.index') }}">Laporan</a></li>
                        <li><a class="dropdown-item" href="/">Keluar</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>


    {{-- Konten --}}
    <main class="py-4">
        @yield('content')
        @yield('scripts')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
