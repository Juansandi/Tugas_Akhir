@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="row g-0 auth-card shadow-lg rounded-3 overflow-hidden">

    {{-- FORM --}}
    <div class="col-md-6 d-flex align-items-center">
        <div class="p-5 w-100">

            <h2 class="fw-bold mb-2">Buat Akun ✨</h2>
            <p class="text-muted mb-4">
                Lengkapi data berikut untuk membuat akun baru
            </p>

            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Nama Pengguna</label>
                    <input type="text"
                           class="form-control form-control-lg"
                           name="username"
                           placeholder="Masukan nama pengguna Anda"
                           required>
                    <div class="invalid-feedback">
                        Nama pengguna wajib diisi.
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Email</label>
                    <input type="email"
                           class="form-control form-control-lg"
                           name="email"
                           placeholder="Masukan email Anda"
                           required>
                    <div class="invalid-feedback">
                        Email wajib diisi.  
                    </div>
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Nomor Telepon</label>
                    <input type="text"
                           class="form-control form-control-lg"
                           name="no_telp"
                           placeholder="e.g. 08123456789"
                           required>
                    <div class="invalid-feedback">
                        Nomor telepon wajib diisi.
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3 password-container">
                    <label class="form-label small text-muted">Kata Sandi</label>
                    <input type="password"
                           class="form-control form-control-lg"
                           id="password"
                           name="password"
                           placeholder="Masukan kata sandi Anda"
                           required>
                    <div class="invalid-feedback">
                        Kata sandi wajib diisi.
                    </div>
                    <button type="button"
                            class="password-toggle"
                            onclick="togglePassword('password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3 password-container">
                    <label class="form-label small text-muted">Konfirmasi Kata Sandi</label>
                    <input type="password"
                           class="form-control form-control-lg"
                           id="confirm_password"
                           name="password_confirmation"
                           placeholder="Masukan ulang kata sandi Anda"
                           required>
                    <div class="invalid-feedback">
                        Konfirmasi kata sandi wajib diisi.
                    </div>
                    <button type="button"
                            class="password-toggle"
                            onclick="togglePassword('confirm_password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>


                {{-- Button --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-dark btn-lg">
                        Daftar
                    </button>
                </div>

                <p class="text-muted small mt-4">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="fw-semibold auth-link">
                        Masuk di sini
                    </a>
                </p>

            </form>
        </div>
    </div>

    {{-- IMAGE --}}
    <div class="col-md-6 d-none d-md-block">
        <img src="https://images.unsplash.com/photo-1473648717346-73c9c15cbad6?auto=format&fit=crop&w=1200&q=80"
             alt="Fresh vegetables"
             class="img-fluid h-100 auth-image">
    </div>

</div>
@endsection
