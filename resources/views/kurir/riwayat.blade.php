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
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@foreach($tugas as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>#{{ $item->pesanan->id }}</td>
    <td>{{ ucfirst($item->status) }}</td>
    <td>{{ $item->updated_at->format('d M Y H:i') }}</td>

    <td>
        {{-- 🔽 TAMBAHKAN TOMBOL DETAIL --}}
        <a href="{{ route('kurir.pesanan.detail', $item->id) }}"
           class="btn btn-sm btn-outline-primary">
            📦 Detail
        </a>
    </td>
</tr>
@endforeach
</tbody>
</table>

@endsection
