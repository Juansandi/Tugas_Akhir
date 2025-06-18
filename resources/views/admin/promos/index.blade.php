@extends('layouts.admin')

@section('title', 'Promo Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Promo</h2>
        <a href="{{ route('promos.create') }}" class="btn btn-dark">Tambah Promo</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>Kode Promo</th>
                    <th>Nama Promo</th>
                    <th>Diskon</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $index => $promo)
                    <tr>
                        <td>{{ $promo->kode_promo }}</td>
                        <td>{{ $promo->nama_promo }}</td>
                        <td>{{ $promo->diskon }} %</td>
                        <td>{{ Str::limit($promo->deskripsi, 20) }}</td>
                        <td>{{ $promo->mulai }}</td>
                        <td>{{ $promo->akhir }}</td>
                        <td>
                            <a href="{{ route('promos.edit', $promo->id) }}" class="text-primary">Edit</a> |
                            <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted">Belum ada promo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    
</div>
@endsection
