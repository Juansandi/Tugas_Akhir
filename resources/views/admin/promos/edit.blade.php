@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
<div class="container mt-4">
    <h2>Edit Promo</h2>
    <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Kode Promo</label>
            <input type="text" name="kode_promo" value="{{ $promo->kode_promo }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Nama Promo</label>
            <input type="text" name="nama_promo" value="{{ $promo->nama_promo }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Diskon</label>
            <input type="number" name="diskon" value="{{ $promo->diskon }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="mulai" value="{{ $promo->mulai }}" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Tanggal Akhir</label>
            <input type="date" name="akhir" value="{{ $promo->akhir }}" class="form-control" required>
        </div>
      
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.promos.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
