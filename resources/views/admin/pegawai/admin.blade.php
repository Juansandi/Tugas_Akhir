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
                <th>Status</th>
                <th>Aksi</th>
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

                <td>
                    @if($item->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>

                <td>
                    @if($item->is_active)
                    <form action="{{ route('admin.user.toggle',$item->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menonaktifkan akun ini?')">
                            Nonaktifkan
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.user.toggle',$item->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-success">
                            Aktifkan
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('admin.pegawai.modal-tambah', ['role' => 'admin'])
@endsection
