@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Tambah Promo</h2>

    <form action="{{ route('promos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="kode_promo" class="form-label">Kode Promo</label>
            <input type="text" name="kode_promo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama_promo" class="form-label">Nama Promo</label>
            <input type="text" name="nama_promo" class="form-control">
        </div>

        <div class="mb-3">
            <label for="diskon" class="form-label">Diskon</label>
            <input type="number" name="diskon" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="mulai" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="akhir" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('promos.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
