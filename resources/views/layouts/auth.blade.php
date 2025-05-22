<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Farm Fresh</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .auth-container {
            max-width: 1100px;
            margin: 2rem auto;
        }
        
        .auth-card {
            background-color: #fff;
            border: none;
            border-radius: 0;
            overflow: hidden;
        }
        
        .auth-image {
            max-height: 500px;
            object-fit: cover;
        }
        
        .form-control {
            border-radius: 4px;
            padding: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        
        .btn-dark {
            border-radius: 4px;
            padding: 0.75rem;
            width: 100%;
            background-color: #212529;
        }
        
        .auth-link {
            font-size: 0.9rem;
        }
        
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            background: none;
            border: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }
        
        .footer {
            background-color: #212529;
            color: #f8f9fa;
            padding: 2rem 0;
        }
        
        .footer-logo {
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="py-3">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <svg width="50" height="30" viewBox="0 0 50 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 0C29.7 0 34.5 4 34.5 8.6C39.5 8.6 43.5 12.6 43.5 17.6C43.5 22.6 39.5 26.6 34.5 26.6H15.5C10.5 26.6 6.5 22.6 6.5 17.6C6.5 12.6 10.5 8.6 15.5 8.6C15.5 4 20.3 0 25 0Z" fill="#000"/>
                        </svg>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Blog</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <div class="container auth-container">
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="footer-logo">Toko Beras & Sayur</div>
                    <p>Address: Jl.Babarsari 3, Yogyakarta, Indonesia</p>
                    <p>Phone number: +628123-4567-890</p>
                    <p>Email: tokoberassayur@gmail.com</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Home</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Products</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>LEGAL PAGES</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum Text</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                        <li><a href="#">Lorem Ipsum</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-top pt-3 mt-4 text-center">
                <p>Copyright © 2025 Toko Beras Sayur, Inc</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>