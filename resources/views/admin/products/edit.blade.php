@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="container mt-4">
    <h2>Edit Produk</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Merk</label>
            <input type="text" name="jenis" value="{{ $product->jenis }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                @foreach ($categories as $kat)
                    <option value="{{ $kat->id }}" {{ $product->kategori_id == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="{{ $product->harga }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ $product->stok }}" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ $product->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label>Gambar Produk</label>
            <input type="file" name="image" class="form-control">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="100" class="mt-2">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
