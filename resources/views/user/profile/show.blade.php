@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm p-4 text-center">
        {{-- Icon profil dengan inisial --}}
        <div class="mx-auto mb-3" 
             style="width: 100px; height: 100px; 
                    line-height: 100px; 
                    font-size: 3.5rem; 
                    font-weight: 700; 
                    color: white; 
                    background-color: #0d6efd; 
                    border-radius: 50%;">
            {{ strtoupper(substr($user->username, 0, 1)) }}
        </div>

        <h4 class="mb-3">{{ $user->username }}</h4>

        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>No. Telepon:</strong> {{ $user->no_telp ?? '-' }}</p>
        <h4>Daftar Alamat</h4>

@if ($user->alamatPengguna->isEmpty())
    <p>Belum ada alamat.</p>
@else
    <ul>
        @foreach ($user->alamatPengguna as $alamat)
            <li>
                {{ $alamat->label }} – {{ $alamat->alamat }}
                @if ($alamat->is_default)
                    <strong>(Utama)</strong>
                @endif
            </li>
        @endforeach
    </ul>
@endif
<a href="{{ route('alamat.index') }}" class="btn btn-sm btn-outline-success mt-2">
    <i class="bi bi-geo-alt me-1"></i> Kelola Alamat
</a>

        <p><strong>Jumlah Poin:</strong> {{ $user->jumlah_poin }}</p>

        <div class="d-flex gap-2 mt-4">
            <button type="button" class="btn btn-outline-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="bi bi-pencil-square me-2"></i> Edit Profil
            </button>
            <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                <i class="bi bi-key me-2"></i> Ubah Password
            </button>
        </div>
    </div>
</div>

@include('user.profile.edit')
@include('user.profile.edit_password')
@endsection
