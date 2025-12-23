@extends('layouts.kurir')

@section('title', 'Pesanan Aktif')

@section('content')
<h4>Pesanan Aktif</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>ID Pesanan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tugas as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>#{{ $item->pesanan->id }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td>
                <form action="{{ route('kurir.pesanan.selesai', $item->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        Selesai
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted">
                Tidak ada pesanan
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
