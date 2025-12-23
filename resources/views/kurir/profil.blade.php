@extends('layouts.kurir')

@section('title', 'Profil Kurir')

@section('content')
<h4>Profil Kurir</h4>

<div class="card">
    <div class="card-body">
        <p><strong>Username:</strong> {{ auth()->user()->username }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>No. Telepon:</strong> {{ auth()->user()->no_telp }}</p>

        <a href="#" class="btn btn-sm btn-primary">
            Ubah Profil
        </a>
    </div>
</div>
@endsection
