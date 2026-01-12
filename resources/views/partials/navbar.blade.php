@php
use App\Models\UserNotification;
use App\Models\Cart;

$userNotifs = collect();
$cartCount = 0;

if (Auth::check()) {
    $userNotifs = UserNotification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->latest()
        ->get();

    // 🔥 JUMLAH ITEM UNIK DI CART
    $cartCount = Cart::where('user_id', Auth::id())->count();
}
@endphp


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/FreshGO.png') }}" alt="TokoSayur" height="40"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('paket.index') }}">Paket Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('wishlist.index') }}"><i class="bi bi-heart"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart"></i>
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                {{ $cartCount }}
                                <span class="visually-hidden">jumlah item di keranjang</span>
                            </span>
                        @endif
                    </a>
                </li>
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if($userNotifs->count())
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">

                                {{ $userNotifs->count() }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        @forelse ($userNotifs as $notif)
                            <li>
                                <a class="dropdown-item" href="{{ route('user.notifications.read', $notif->id) }}">
                                    {{ Str::limit($notif->pesan, 60) }}
                                    <br>
                                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @empty
                            <li><span class="dropdown-item text-center">Tidak ada notifikasi</span></li>
                        @endforelse

                        @if($userNotifs->count())
                        <li>
                            <form action="{{ route('user.notifications.readAll') }}" method="POST" class="text-center">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm">Tandai semua sebagai dibaca</button>
                            </form>
                        </li>
                        @endif
                        <li class="px-3 py-2 text-center">
                                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-outline-success w-100">
                                    <i class="bi bi-list"></i> Lihat Semua
                                </a>
                            </li>
                    </ul>
                </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login.form') }}">Login</a>
                    </li>
                @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('pesanan.history') }}">Riwayat</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endguest
            </ul>
        </div>
    </div>
</nav>
