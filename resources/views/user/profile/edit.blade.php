@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Edit Profil</h1>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                name="username" 
                id="username" 
                class="form-control" 
                value="{{ old('username', $user->username) }}" 
                required
            >
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control" 
                value="{{ old('email', $user->email) }}" 
                required
            >
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input 
                type="text" 
                name="alamat" 
                id="alamat" 
                class="form-control" 
                value="{{ old('alamat', $user->alamat) }}"
            >
        </div>

        <div class="mb-3">
            <label for="no_telp" class="form-label">No Telepon</label>
            <input 
                type="text" 
                name="no_telp" 
                id="no_telp" 
                class="form-control" 
                value="{{ old('no_telp', $user->no_telp) }}"
            >
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
