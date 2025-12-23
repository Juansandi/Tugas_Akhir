@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')
<h4>Riwayat Pengiriman</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>ID Pesanan</th>
            <th>Status</th>
            <th>Tanggal Selesai</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tugas as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>#{{ $item->pesanan->id }}</td>
            <td>
                <span class="badge bg-success">Selesai</span>
            </td>
            <td>{{ $item->updated_at->format('d-m-Y H:i') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted">
                Belum ada riwayat pengiriman
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
