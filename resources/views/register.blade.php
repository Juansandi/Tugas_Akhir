@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="row g-0 auth-card shadow-lg rounded-3 overflow-hidden">

    {{-- FORM --}}
    <div class="col-md-6 d-flex align-items-center">
        <div class="p-5 w-100">

            <h2 class="fw-bold mb-2">Create Account ✨</h2>
            <p class="text-muted mb-4">
                Please fill the form below to register
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Username --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Username</label>
                    <input type="text"
                           class="form-control form-control-lg"
                           name="username"
                           placeholder="Choose a username"
                           required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Email</label>
                    <input type="email"
                           class="form-control form-control-lg"
                           name="email"
                           placeholder="Enter your email"
                           required>
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label small text-muted">Phone Number</label>
                    <input type="text"
                           class="form-control form-control-lg"
                           name="no_telp"
                           placeholder="e.g. 08123456789"
                           required>
                </div>

                {{-- Password --}}
                <div class="mb-3 password-container">
                    <label class="form-label small text-muted">Password</label>
                    <input type="password"
                           class="form-control form-control-lg"
                           id="password"
                           name="password"
                           placeholder="Create a password"
                           required>
                    <button type="button"
                            class="password-toggle"
                            onclick="togglePassword('password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3 password-container">
                    <label class="form-label small text-muted">Confirm Password</label>
                    <input type="password"
                           class="form-control form-control-lg"
                           id="confirm_password"
                           name="password_confirmation"
                           placeholder="Repeat your password"
                           required>
                    <button type="button"
                            class="password-toggle"
                            onclick="togglePassword('confirm_password')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                {{-- Terms --}}
                <div class="mb-3 form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           id="terms"
                           required>
                    <label class="form-check-label small" for="terms">
                        I agree to the
                        <a href="#" class="auth-link">Terms of Service</a> and
                        <a href="#" class="auth-link">Privacy Policy</a>
                    </label>
                </div>

                {{-- Button --}}
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-dark btn-lg">
                        Create Account
                    </button>
                </div>

                <p class="text-muted small mt-4">
                    Already have an account?
                    <a href="{{ route('login') }}" class="fw-semibold auth-link">
                        Login here
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
