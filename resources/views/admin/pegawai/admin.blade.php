@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Daftar Admin</h4>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPengguna">
        Tambah Admin
    </button>

    {{-- Tabel --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    <span class="badge bg-primary">Admin</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('admin.pegawai.modal-tambah', ['role' => 'admin'])
@endsection
