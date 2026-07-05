@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="row g-0 auth-card overflow-hidden">
    {{-- FORM --}}
    <div class="col-lg-5 d-flex align-items-center">
        <div class="w-100 p-5">
            <div class="mb-4">
                <span class="badge bg-success px-3 py-2 mb-3">
                    <i class="bi bi-shop me-1"></i>
                    Toko Bahan Pokok
                </span>
                <h2 class="fw-bold mb-2">
                    Buat Akun
                </h2>
                <p class="text-muted mb-0">
                    Lengkapi data berikut untuk membuat akun baru.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('register') }}"
                  class="needs-validation"
                  novalidate>
                @csrf
                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label">
                        Nama Pengguna
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>

                        <input
                            type="text"
                            name="username"
                            class="form-control form-control-lg"
                            placeholder="Masukkan nama pengguna"
                            required>
                        <div class="invalid-feedback">
                            Nama pengguna wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">
                        Email
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-lg"
                            placeholder="Masukkan email"
                            required>
                        <div class="invalid-feedback">
                            Email wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div class="mb-3">
                    <label class="form-label">
                        Nomor Telepon
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input
                            type="text"
                            name="no_telp"
                            class="form-control form-control-lg"
                            placeholder="Contoh: 08123456789"
                            required>
                        <div class="invalid-feedback">
                            Nomor telepon wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label">
                        Kata Sandi
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control form-control-lg"
                            placeholder="Masukkan kata sandi"
                            required>
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>

                        <div class="invalid-feedback">
                            Kata sandi wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-4">
                    <label class="form-label">
                        Konfirmasi Kata Sandi
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>

                        <input
                            type="password"
                            id="confirm_password"
                            name="password_confirmation"
                            class="form-control form-control-lg"
                            placeholder="Masukkan kembali kata sandi"
                            required>
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('confirm_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="invalid-feedback">
                            Konfirmasi kata sandi wajib diisi.
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button class="btn btn-dark btn-lg">
                        <i class="bi bi-person-plus me-2"></i>
                        Daftar
                    </button>
                </div>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
                Sudah memiliki akun?
                <a href="{{ route('login') }}"
                   class="fw-semibold text-decoration-none">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    {{-- IMAGE --}}
    <div class="col-lg-7 d-none d-lg-block position-relative">
        <img
            src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1400&q=80"
            class="auth-image"
            alt="Toko Bahan Pokok">

        <div class="image-overlay">
            <div>
                <h2 class="fw-bold text-white">
                    Mulai Belanja Sekarang
                </h2>
                <p class="text-white-50 mb-0">
                    Daftarkan akun Anda untuk menikmati kemudahan
                    berbelanja kebutuhan bahan pokok secara online.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection