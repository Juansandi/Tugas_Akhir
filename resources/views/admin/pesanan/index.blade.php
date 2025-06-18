@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Pesanan</h2>
        {{-- Tambahkan tombol jika ingin fitur seperti export atau tambah manual --}}
        {{-- <a href="#" class="btn btn-dark">Tambah Pesanan</a> --}}
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananList as $index => $pesanan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pesanan->pengguna->username ?? 'Guest' }}</td>
                        <td>
                            @php
                                $status = $pesanan->status;
                                $badgeClass = match($status) {
                                    'menunggu konfirmasi' => 'bg-secondary text-light',
                                    'diproses'            => 'bg-primary text-light',
                                    'dikirim'             => 'bg-info text-dark',
                                    'diterima'            => 'bg-warning text-dark',
                                    'selesai'             => 'bg-success text-light',
                                    default               => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            {{-- Tambahan aksi admin jika diperlukan, misalnya ubah status --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
