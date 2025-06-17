@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Riwayat Pesanan Saya</h4>

    @if ($pesanan->isEmpty())
        <p>Belum ada pesanan.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal Pesan</th>
                    <th>Status</th>
                    <th>Total Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan as $pesanan)
                <tr>
                    <td>{{ $pesanan->id }}</td>
                    <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ ucfirst($pesanan->status) }}</td>
                    <td>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('pesanan.show', $pesanan->id) }}" class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
</div>
@endsection
