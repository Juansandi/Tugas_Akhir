<style>
    .notif-item {
        white-space: normal;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* maksimal 2 baris */
        -webkit-box-orient: vertical;
        line-height: 1.3;
        font-size: 0.875rem;
    }

    .notif-time {
        font-size: 0.75rem;
        color: #adb5bd;
    }

    .dropdown-item.notif {
        padding-top: 8px;
        padding-bottom: 8px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        {{-- LOGO --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/FreshGO.png') }}" alt="FreshGO" height="40">
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                {{-- MENU UTAMA --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products') }}">Produk</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('paket.index') }}">Paket</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Tentang</a>
                </li>

                {{-- WISHLIST --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-heart"></i>
                    </a>
                </li>

                {{-- CART --}}
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart"></i>

                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>

                {{-- ================= AUTH ================= --}}
                @auth

                {{-- NOTIFICATION --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative"
                       href="#"
                       id="notifDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        <i class="bi bi-bell"></i>

                        @if($navbarNotifCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                {{ $navbarNotifCount }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                        aria-labelledby="notifDropdown"
                        style="width:320px; max-height:420px; overflow-y:auto;">

                        {{-- LIST NOTIFIKASI --}}
                        @forelse ($navbarNotifs as $notif)
                            <li>
                                <a class="dropdown-item notif"
                                href="{{ route('user.notifications.read', $notif->id) }}">

                                    <div class="notif-item" title="{{ $notif->pesan }}">
                                        {{ $notif->pesan }}
                                    </div>

                                    <div class="notif-time">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </div>

                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @empty
                            <li>
                                <span class="dropdown-item text-center text-muted">
                                    Tidak ada notifikasi
                                </span>
                            </li>
                        @endforelse

                        {{-- ACTIONS --}}
                        @if($navbarNotifCount > 0)
                            <li class="px-3 py-2 text-center">
                                <form action="{{ route('user.notifications.readAll') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-link btn-sm">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            </li>
                        @endif

                        <li class="px-3 pb-2 text-center">
                            <a href="{{ route('user.notifications.index') }}"
                            class="btn btn-sm btn-outline-success w-100">
                                <i class="bi bi-list"></i> Lihat Semua
                            </a>
                        </li>

                    </ul>
                </li>

                {{-- PROFILE --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        {{ Auth::user()->username }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pesanan.history') }}">
                                Riwayat Pesanan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="dropdown-item text-danger">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

                @else
                {{-- GUEST --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login.form') }}">
                        Masuk
                    </a>
                </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
