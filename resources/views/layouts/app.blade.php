<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Toko Sayur')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* ==========================
        CARD PRODUK
        ========================== */
        .product-card{
            border-radius:14px;
            overflow:hidden;
            transition:.3s ease;
        }
        .product-card:hover{
            transform:translateY(-6px);
            box-shadow:0 .8rem 1.8rem rgba(0,0,0,.12)!important;
        }
        .product-card img{
            transition:.3s ease;
        }
        .product-card:hover img{
            transform:scale(1.04);
        }

        /* ==========================
        NAMA PRODUK
        ========================== */
        .product-name{
            min-height:48px;
            display:-webkit-box;
            -webkit-line-clamp:2;
            -webkit-box-orient:vertical;
            overflow:hidden;
        }

        /* ==========================
        TOMBOL
        ========================== */
        .product-card .btn{
            border-radius:8px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    @include('partials.navbar')
    @yield('hero')
    <main class="flex-grow-1">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
