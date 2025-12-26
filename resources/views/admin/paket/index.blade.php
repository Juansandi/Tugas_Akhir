@extends('layouts.admin')

@section('title', 'Manajemen Paket')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Manajemen Paket Produk</h4>
        <a href="{{ route('admin.paket.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Paket
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($pakets as $paket)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">

                {{-- IMAGE --}}
                @if($paket->image)
                    <img src="{{ asset('storage/'.$paket->image) }}"
                         class="card-img-top"
                         style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="height: 180px;">
                        <span class="text-muted">No Image</span>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <h5 class="fw-semibold">{{ $paket->nama_paket }}</h5>

                    <p class="mb-1">
                        <strong>Rp {{ number_format($paket->harga_paket,0,',','.') }}</strong>
                    </p>

                    {{-- STATUS --}}
                    <span class="badge 
                        {{ $paket->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}
                        mb-2">
                        {{ ucfirst($paket->status) }}
                    </span>

                    {{-- DESKRIPSI --}}
                    @if($paket->deskripsi)
                        <p class="text-muted small mb-2">
                            {{ Str::limit($paket->deskripsi, 80) }}
                        </p>
                    @endif

                    {{-- ISI PAKET --}}
                    <div class="mt-2">
                        <strong>Isi Paket:</strong>
                        <ul class="small ps-3 mb-2">
                            @foreach($paket->detailPakets->take(3) as $item)
                                <li>
                                    {{ $item->produk->nama_produk }}
                                    @if($item->size)
                                        ({{ $item->size->size }})
                                    @endif
                                    × {{ $item->quantity }}
                                </li>
                            @endforeach

                            @if($paket->detailPakets->count() > 3)
                                <li class="text-muted">dan lainnya…</li>
                            @endif
                        </ul>
                    </div>

                    {{-- ACTION --}}
                    <div class="mt-auto d-flex gap-2">
                        <a href="{{ route('admin.paket.edit', $paket->id) }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                        <form action="{{ route('admin.paket.destroy', $paket->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus paket ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Belum ada paket.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
