@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">Edit Produk</h2>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ========================= --}}
        {{-- INFORMASI PRODUK --}}
        {{-- ========================= --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Produk</h5>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text"
                           name="nama_produk"
                           class="form-control"
                           value="{{ $product->nama_produk }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Merk</label>
                    <input type="text"
                           name="jenis"
                           class="form-control"
                           value="{{ $product->jenis }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        @foreach ($categories as $kat)
                            <option value="{{ $kat->id }}"
                                {{ $product->kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi"
                              class="form-control"
                              rows="3">{{ $product->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" name="image" class="form-control">
                    @if ($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}"
                             class="mt-2 rounded"
                             width="120">
                    @endif
                </div>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- UKURAN, HARGA & STOK --}}
        {{-- ========================= --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Ukuran, Harga & Stok</h5>

                <small class="text-muted d-block mb-3">
                    Harga dan stok dikelola per ukuran produk.
                </small>

                <div id="sizeContainer">
                    @foreach ($product->sizes as $i => $size)
                        <div class="size-row border rounded p-3 mb-3">
                            <input type="hidden"
                                   name="sizes[{{ $i }}][id]"
                                   value="{{ $size->id }}">

                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Ukuran</label>
                                    <input type="text"
                                           name="sizes[{{ $i }}][size]"
                                           class="form-control"
                                           value="{{ $size->size }}"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Harga</label>
                                    <input type="number"
                                           name="sizes[{{ $i }}][harga]"
                                           class="form-control"
                                           value="{{ $size->harga }}"
                                           required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number"
                                           name="sizes[{{ $i }}][stok]"
                                           class="form-control"
                                           value="{{ $size->stok }}"
                                           required>
                                </div>

                                <div class="col-md-1">
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="hapusSize(this)">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        class="btn btn-outline-primary btn-sm"
                        onclick="tambahSize()">
                    + Tambah Ukuran
                </button>
            </div>
        </div>

        {{-- ========================= --}}
        {{-- AKSI --}}
        {{-- ========================= --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Simpan Perubahan
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>
</div>

{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>
let sizeIndex = {{ $product->sizes->count() }};

function tambahSize() {
    const container = document.getElementById('sizeContainer');

    const html = `
        <div class="size-row border rounded p-3 mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Ukuran</label>
                    <input type="text"
                           name="sizes[${sizeIndex}][size]"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga</label>
                    <input type="number"
                           name="sizes[${sizeIndex}][harga]"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stok</label>
                    <input type="number"
                           name="sizes[${sizeIndex}][stok]"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-1">
                    <button type="button"
                            class="btn btn-outline-danger"
                            onclick="hapusSize(this)">
                        ✕
                    </button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    sizeIndex++;
}

function hapusSize(button) {
    if (confirm('Hapus ukuran ini?')) {
        button.closest('.size-row').remove();
    }
}
</script>
@endsection
