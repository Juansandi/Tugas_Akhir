@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="row g-0 auth-card shadow-lg rounded-3 overflow-hidden">

    {{-- FORM --}}
    <div class="col-md-6 d-flex align-items-center">
        <div class="p-5 w-100">

            <h2 class="fw-bold mb-2">Selamat Datang 👋</h2>
            <p class="text-muted mb-4">
                Silakan masuk ke akun Anda
            </p>

            <form method="POST" action="{{ route('login') }}">  
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Username</label>
                    <input type="text"
                           class="form-control form-control-lg"
                           name="username"
                           placeholder="Masukan username Anda"
                           required>
                </div>

                {{-- Password --}}
                <div class="mb-3 password-container">
                    <label class="form-label small text-muted">Password</label>
                    <input type="password"
                           class="form-control form-control-lg"
                           id="password"
                           name="password"
                           placeholder="Masukan password Anda"
                           required>
                    <button type="button" class="password-toggle"
                            onclick="togglePassword('password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                {{-- Button --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-dark btn-lg">
                        Masuk
                    </button>
                </div>

                <div class="text-end mt-3">
                    <a href="#" class="auth-link">Lupa Password?</a>
                </div>
            </form>

            <hr class="my-4">

            <p class="text-muted small">
                Belum punya akun?
                <a href="{{ route('register.form') }}" class="fw-semibold text-dark">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>

    {{-- IMAGE --}}
    <div class="col-md-6 d-none d-md-block">
        <img src="https://images.unsplash.com/photo-1447175008436-054170c2e979?auto=format&fit=crop&w=1200&q=80"
             alt="Fresh vegetables"
             class="img-fluid h-100 auth-image">
    </div>

</div>
@endsection
