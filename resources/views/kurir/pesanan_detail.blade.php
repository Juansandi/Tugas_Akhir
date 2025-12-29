@extends('layouts.kurir')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">

    <h4 class="mb-3">
        Detail Pesanan #{{ $tugas->pesanan->id }}
    </h4>

    {{-- STATUS --}}
    <span class="badge bg-info mb-3">
        {{ ucfirst($tugas->status) }}
    </span>

    {{-- ALAMAT --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Alamat Customer</h6>
            <p class="mb-0">
                {{ $tugas->pesanan->pengguna->alamat ?? '-' }}
            </p>
        </div>
    </div>

    {{-- PRODUK --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6>Produk Pesanan</h6>

            <ul class="list-group list-group-flush">
            @foreach($tugas->pesanan->detail as $item)
                <li class="list-group-item">
                    @if($item->type === 'produk')
                        {{ $item->produk->nama_produk }}
                        ({{ $item->size->size }})
                        × {{ $item->quantity }}
                    @else
                        {{ $item->paket->nama_paket }}
                        × {{ $item->quantity }}
                        <span class="badge bg-info ms-2">Paket</span>
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="card mb-3">
        <div class="card-body">
            <strong>Total Pesanan:</strong>
            Rp {{ number_format($tugas->pesanan->total, 0, ',', '.') }}
        </div>
    </div>

    {{-- AKSI --}}
    <div class="d-flex gap-2">

        <a href="{{ route('kurir.chat.show', $tugas->pesanan->chatKurir->id) }}"
           class="btn btn-outline-success">
            💬 Chat Customer
        </a>

        @if($tugas->status === 'aktif')
            <form method="POST"
                  action="{{ route('kurir.selesai', $tugas->id) }}">
                @csrf
                <button class="btn btn-success">
                    ✅ Tandai Selesai
                </button>
            </form>
        @endif

        <a href="{{ route('kurir.pesanan') }}"
           class="btn btn-outline-secondary">
            ← Kembali
        </a>

    </div>

</div>
@endsection
