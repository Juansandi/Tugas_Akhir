<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">TokoSayur</a>
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
                    <a class="nav-link" href="#">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Tentang</a>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login.form') }}">Login</a>
                    </li>
                @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profil</a></li>
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
