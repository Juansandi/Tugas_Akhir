@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="row g-0 auth-card shadow">
    <div class="col-md-6">
        <div class="p-5">
            <h2 class="mb-3">Login</h2>
            <p class="text-muted mb-4">Do not have an account? <a href="{{ route('register.form') }}" class="text-dark">Create an account</a></p>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Enter Your Username" required>
                </div>
                <div class="mb-3 password-container">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-dark">Login</button>
                </div>
                <div class="text-end mt-3">
                    <a href="#" class="text-dark auth-link">Forgot Your Password?</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1447175008436-054170c2e979?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1999&q=80" alt="Fresh carrots" class="img-fluid h-100 auth-image">
    </div>
</div>
@endsection