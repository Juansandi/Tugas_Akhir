@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Pengguna</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Jumlah Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggunas as $index => $pengguna)
                    <tr>
                        <td>{{ $pengguna->id }}</td>
                        <td>{{ $pengguna->username }}</td>
                        <td>{{ $pengguna->no_telp }}</td>
                        <td>
                            {{ optional($pengguna->alamatUtama)->alamat ?? '-' }}
                        </td>
                        <td>{{ $pengguna->jumlah_poin }}</td>
                        <td>
                            <a href="{{ route('admin.pengguna.riwayat', $pengguna->id) }}" class="btn btn-sm btn-primary">
                                Lihat Riwayat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
