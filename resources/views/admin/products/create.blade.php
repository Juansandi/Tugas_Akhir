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

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Produk</h5>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Merk</label>
                    <input type="text" name="jenis" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{--  BAGIAN UKURAN PRODUK --}}
        {{-- ===================== --}}

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Ukuran, Harga & Stok</h5>

                <small class="text-muted d-block mb-3">
                    Setiap produk minimal memiliki satu ukuran.
                </small>

                <div id="size-wrapper">
                    <div class="size-row border rounded p-3 mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Ukuran</label>
                                <input type="text" name="sizes[0][size]" class="form-control" placeholder="Contoh: 1 kg" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga</label>
                                <input type="number" name="sizes[0][harga]" class="form-control" placeholder="Harga" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="sizes[0][stok]" class="form-control" placeholder="Stok" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-size" class="btn btn-outline-primary btn-sm">
                    + Tambah Ukuran
                </button>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Simpan Produk
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Batal
            </a>
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
