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
            <label>Harga Default</label>
            <input type="number" name="harga" value="{{ $product->harga }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stok Default</label>
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

    <input type="hidden" id="initialSizeCount" value="{{ $product->sizes->count() }}">
        {{-- ====================== --}}
        {{-- EDIT / TAMBAH UKURAN --}}
        {{-- ====================== --}}
        <h4 class="mt-4">Ukuran Produk</h4>

        <div id="sizeContainer">

            {{-- Ukuran Lama --}}
            @foreach ($product->sizes as $i => $size)
            <div class="row mb-2 p-2 border rounded">
                <input type="hidden" name="sizes[{{ $i }}][id]" value="{{ $size->id }}">

                <div class="col-md-3">
                    <label>Ukuran</label>
                    <input type="text" name="sizes[{{ $i }}][size]" class="form-control" value="{{ $size->size }}">
                </div>

                <div class="col-md-3">
                    <label>Harga</label>
                    <input type="number" name="sizes[{{ $i }}][harga]" class="form-control" value="{{ $size->harga }}">
                </div>

                <div class="col-md-3">
                    <label>Stok</label>
                    <input type="number" name="sizes[{{ $i }}][stok]" class="form-control" value="{{ $size->stok }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100" onclick="hapusSize(this)">
                        Hapus
                    </button>
                </div>
            </div>
            @endforeach

        </div>

        <button type="button" class="btn btn-outline-primary mt-3" onclick="tambahSize()">
            + Tambah Ukuran Baru
        </button>

        <hr>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<script>
    // Ambil jumlah initial size dari hidden input
    let sizeIndex = parseInt(document.getElementById('initialSizeCount').value);

    console.log("Jumlah size awal:", sizeIndex);

    function addSizeField() {
        sizeIndex++;

        const container = document.getElementById('sizesContainer');

        const newField = document.createElement('div');
        newField.classList.add('mb-2');

        newField.innerHTML = `
            <input type="text" name="sizes[]" class="form-control mb-2" placeholder="Masukkan ukuran baru">
        `;

        container.appendChild(newField);
    }
</script>
@endsection