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
        body{
            min-height:100vh;
            background:#f4f6f9;
            color:#212529;
        }

        /* ===========================
        CONTAINER
        =========================== */
        .auth-container{
            max-width:1100px;
            margin:60px auto;
        }
        .auth-card{
            background:#fff;
            border:none;
            border-radius:20px;
            overflow:hidden;
            min-height:620px;
            box-shadow:
                0 20px 45px rgba(0,0,0,.08);
        }

        /* ===========================
        FORM
        =========================== */
        .form-label{
            font-weight:500;
            margin-bottom:.5rem;
        }
        .form-control-lg{
            height:55px;
            border-radius:10px;
        }
        .form-control:focus{
            box-shadow:none;
        }
        .input-group-text{
            background:#fff;
            border-right:none;
        }
        .input-group .form-control{
            border-left:none;
        }
        .input-group:focus-within .input-group-text{
            border-color:#86b7fe;
        }
        .form-control:focus{
            border-color:#86b7fe;
        }

        /* ===========================
        BUTTON
        =========================== */
        .btn-dark{
            border-radius:10px;
            height:55px;
            transition:.25s;
        }
        .btn-dark:hover{
            transform:translateY(-2px);
        }

        /* ===========================
        IMAGE
        =========================== */
        .auth-image{
            width:100%;
            height:100%;
            object-fit:cover;
        }
        .image-overlay{
            position:absolute;
            inset:0;
            display:flex;
            align-items:flex-end;
            padding:50px;
            background:linear-gradient(
                rgba(0,0,0,.15),
                rgba(0,0,0,.45)
            );
        }

        /* ===========================
        HEADER
        =========================== */
        header{
            background:#fff;
            border-bottom:1px solid #ececec;
        }
        .navbar-brand{
            font-size:1.55rem;
        }
        .nav-link{
            font-weight:500;
            transition:.25s;
        }
        .nav-link:hover{
            color:#198754 !important;
        }

        /* ===========================
        FOOTER
        =========================== */
        footer{
            background:#212529;
            color:#fff;
            padding:40px 0;
            margin-top:80px;
        }
        footer a{
            color:#fff;
        }
        footer a:hover{
            color:#adb5bd;
        }

        /* ===========================
        PLACEHOLDER
        ========================== */
        ::placeholder{
            color:#adb5bd !important;
            font-size:.95rem;
        }

        /* ===========================
        PASSWORD
        =========================== */
        .password-toggle:hover{
            color:#212529;
        }

        /* ===========================
        VALIDATION
        =========================== */
        .was-validated .form-control:valid,
        .form-control.is-valid{
            border-color:#dee2e6 !important;
            background-image:none !important;
            box-shadow:none !important;
        }
        .was-validated .form-control:invalid,
        .form-control.is-invalid{
            background-image:none !important;
            box-shadow:none !important;
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
    function togglePassword(id, button) {

    const input = document.getElementById(id);

    const icon = button.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
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
