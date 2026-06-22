<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toko Bahan Pokok</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa, #eef1f4);
            color: #212529;
        }

        /* ===== AUTH CONTAINER ===== */
        .auth-container {
            max-width: 1000px;
            margin: 3rem auto;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 12px;
        }

        /* ===== FORM ===== */
        .form-label {
            font-size: 0.85rem;
        }

        .form-control-lg {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }

        .form-control-lg:focus {
            border-color: #212529;
            box-shadow: none;
        }

        /* ===== BUTTON ===== */
        .btn-dark {
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-dark:hover {
            background-color: #000;
        }

        /* ===== PASSWORD TOGGLE ===== */
        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 55%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
        }

        .password-toggle:hover {
            color: #212529;
        }

        /* ===== LINKS ===== */
        .auth-link {
            font-size: 0.9rem;
            color: #212529;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        /* ===== IMAGE ===== */
        .auth-image {
            object-fit: cover;
            height: 100%;
        }

        /* ===== HEADER ===== */
        header {
            background: #fff;
            border-bottom: 1px solid #eaeaea;
        }

        /* ===== FOOTER ===== */
        footer {
            background-color: #212529;
            color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        footer a {
            color: #f8f9fa;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        ::placeholder {
            font-size:0.9rem;
            color: #adb5bd;
        }

        /* Field valid tetap normal */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #dee2e6 !important;
    background-image: none !important;
    box-shadow: none !important;
}

/* Field tidak valid */
.was-validated .form-control:invalid,
.form-control.is-invalid {
    background-image: none !important;
    box-shadow: none !important;
}
    </style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<header class="py-3">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand fw-bold" href="/">
                Toko Bahan Pokok
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarAuth">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarAuth">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.products') }}">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('paket.index') }}">Paket</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

{{-- ================= CONTENT ================= --}}
<main>
    <div class="container auth-container">
        @yield('content')
    </div>
</main>

{{-- ================= FOOTER ================= --}}
<footer>
    <div class="container">
        <div class="row">

            <div class="col-md-6">
                <h5 class="fw-bold">Toko Bahan Pokok</h5>
                <p class="small mb-0">
                    Sistem Informasi Pengelolaan Transaksi Bahan Pokok Berbasis Website.
                </p>
            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <p class="small mb-0">
                    Email: tokobahanpokok@gmail.com
                </p>
                <p class="small mb-0">
                    Telepon: +62 812-3456-7890
                </p>
            </div>

        </div>

        <div class="border-top pt-3 mt-3 text-center small">
            © {{ date('Y') }} Toko Bahan Pokok. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>

{{-- ================= JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>
<script>
(() => {
    'use strict';

    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });
})();
</script>
</body>
</html>
