@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                ← Kembali
            </a>
            <h4 class="mb-0">Alamat Saya</h4>
        </div>

        <button class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createAlamatModal">
            Tambah Alamat
        </button>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($alamatList->isEmpty())
        <div class="alert alert-info">
            Belum ada alamat. Silakan tambah alamat.
        </div>
    @else
        @foreach ($alamatList as $alamat)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <strong>{{ $alamat->label }}</strong>
                        @if ($alamat->is_default)
                            <span class="badge bg-success ms-2">Utama</span>
                        @endif
                        <p class="mb-1">{{ $alamat->alamat }}</p>
                        <small>No. Telp Penerima: {{ $alamat->no_telp }}</small>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-sm btn-warning mb-1"
                                data-bs-toggle="modal"
                                data-bs-target="#editAlamatModal"
                                data-id="{{ $alamat->id }}"
                                data-label="{{ $alamat->label }}"
                                data-alamat="{{ $alamat->alamat }}"
                                data-telp="{{ $alamat->no_telp }}"
                                data-default="{{ $alamat->is_default }}">
                            Ubah
                        </button>

                        <form action="{{ route('alamat.destroy', $alamat->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- INCLUDE MODAL --}}
@include('user.profile.create_alamat')
@include('user.profile.edit_alamat')
@endsection
