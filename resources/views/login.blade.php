@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="row g-0 auth-card shadow-lg overflow-hidden">

    {{-- FORM LOGIN --}}
    <div class="col-lg-5 d-flex align-items-center">
        <div class="w-100 p-5">
            <div class="mb-4">
                <span class="badge bg-success mb-3 px-3 py-2">
                    <i class="bi bi-shop me-1"></i>
                    Toko Bahan Pokok
                </span>
                <h2 class="fw-bold mb-2">
                    Selamat Datang
                </h2>
                <p class="text-muted mb-0">
                    Masuk untuk melanjutkan ke akun Anda.
                </p>
            </div>

            @if($errors->has('login'))
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ $errors->first('login') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('login') }}"
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
                            value="{{ old('username') }}"
                            class="form-control form-control-lg"
                            placeholder="Masukkan nama pengguna"
                            required>
                        <div class="invalid-feedback">
                            Nama pengguna wajib diisi.
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
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

                <div class="d-grid">
                    <button class="btn btn-dark btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Masuk
                    </button>
                </div>
            </form>
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
                Belum memiliki akun?
                <a href="{{ route('register.form') }}"
                   class="fw-semibold text-decoration-none">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>

    {{-- GAMBAR --}}
    <div class="col-lg-7 d-none d-lg-block position-relative">
        <img
            src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1400&q=80"
            class="auth-image"
            alt="Toko Bahan Pokok">
        <div class="image-overlay">
            <div>
                <h2 class="fw-bold text-white">
                    Belanja Lebih Mudah
                </h2>

                <p class="text-white-50 mb-0">
                    Kelola kebutuhan bahan pokok secara cepat,
                    praktis, dan aman.

                </p>
            </div>
        </div>
    </div>
</div>
@endsection