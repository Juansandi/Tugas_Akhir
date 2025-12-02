@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Tambah Produk</h2>
    @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nama Produk --}}
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
        </div>

        {{-- Merk --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Merk</label>
            <input type="text" name="jenis" class="form-control">
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        {{-- Harga Default --}}
        <div class="mb-3">
            <label for="harga" class="form-label">Harga Default (opsional)</label>
            <input type="number" name="harga" class="form-control">
            <small class="text-muted">Harga ini digunakan jika ukuran tidak memiliki harga khusus.</small>
        </div>

        {{-- Stok Default --}}
        <div class="mb-3">
            <label for="stok" class="form-label">Stok Default (opsional)</label>
            <input type="number" name="stok" class="form-control">
            <small class="text-muted">Stok ini digunakan jika ukuran tidak memiliki stok khusus.</small>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>

        {{-- Gambar Produk --}}
        <div class="mb-3">
            <label for="image" class="form-label">Gambar Produk</label>
            <input type="file" name="image" class="form-control">
        </div>

        {{-- ===================== --}}
        {{--  BAGIAN UKURAN PRODUK --}}
        {{-- ===================== --}}

        <hr class="my-4">
        <h4 class="fw-bold mb-3">Ukuran Produk</h4>

        <div id="size-wrapper">

            {{-- Row ukuran default --}}
            <div class="size-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Ukuran</label>
                        <input type="text" name="sizes[0][size]" class="form-control" placeholder="Contoh: 1 kg">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga</label>
                        <input type="number" name="sizes[0][harga]" class="form-control" placeholder="Harga">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="sizes[0][stok]" class="form-control" placeholder="Stok">
                    </div>
                </div>
            </div>

        </div>

        <button type="button" id="add-size" class="btn btn-sm btn-primary mb-4">
            + Tambah Ukuran
        </button>

        {{-- Submit --}}
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

{{-- SCRIPT TAMBAH ROW UKURAN --}}
<script>
    let index = 1;

    document.getElementById('add-size').addEventListener('click', function () {
        const wrapper = document.getElementById('size-wrapper');

        wrapper.insertAdjacentHTML('beforeend', `
            <div class="size-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Ukuran</label>
                        <input type="text" name="sizes[${index}][size]" class="form-control" placeholder="Contoh: 5 kg">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga</label>
                        <input type="number" name="sizes[${index}][harga]" class="form-control" placeholder="Harga">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" name="sizes[${index}][stok]" class="form-control" placeholder="Stok">
                    </div>
                </div>
            </div>
        `);

        index++;
    });
</script>
@endsection
