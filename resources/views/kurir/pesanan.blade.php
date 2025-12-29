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
        @foreach($tugas as $item)
            @php
                $chat = $item->pesanan->chatKurir;
                $unread = $chat
                    ? $chat->messages
                        ->where('sender_type', 'user')
                        ->where('is_read', false)
                        ->count()
                    : 0;
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>#{{ $item->pesanan->id }}</td>
                <td>{{ ucfirst($item->pesanan->status) }}</td>
                <td>
                    <div class="d-flex gap-2 flex-wrap">

                        {{-- DETAIL PESANAN --}}
                        <a href="{{ route('kurir.pesanan.detail', $item->id) }}"
                        class="btn btn-sm btn-outline-primary">
                            📦 Detail
                        </a>

                        {{-- CHAT --}}
                        @if($chat)
                            <a href="{{ route('kurir.chat.show', $chat->id) }}"
                            class="btn btn-sm btn-outline-success position-relative">
                                💬 Chat

                                @if($unread > 0)
                                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </a>
                        @else
                            <span class="text-muted">Belum tersedia</span>
                        @endif

                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
