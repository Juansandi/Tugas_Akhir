@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Manajemen Produk</h2>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
                Tambah Produk
            </a>
            <button class="btn btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalKategori">
                Kategori
            </button>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text"
                    name="q"
                    class="form-control"
                    placeholder="Cari produk..."
                    value="{{ request('q') }}">
            </div>

            {{-- BAWA FILTER KATEGORI --}}
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif

            <div class="col-auto">
                <button class="btn btn-outline-primary">
                    Cari
                </button>
            </div>

            @if(request('q') || request('kategori'))
                <div class="col-auto">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-link">
                        Reset
                    </a>
                </div>
            @endif
        </div>
    </form>

    {{-- DAFTAR KATEGORI --}}
    <div class="mb-3">
        <strong>Kategori:</strong>

        {{-- SEMUA --}}
        <a href="{{ route('admin.products.index', ['q' => request('q')]) }}"
        class="btn btn-sm {{ request('kategori') ? 'btn-outline-secondary' : 'btn-secondary' }}">
            Semua
        </a>

        @foreach($categories as $category)
            <a href="{{ route('admin.products.index', [
                    'kategori' => $category->id,
                    'q' => request('q')
                ]) }}"
            class="btn btn-sm
                {{ request('kategori') == $category->id
                        ? 'btn-dark'
                        : 'btn-outline-secondary' }}">
                {{ $category->nama_kategori }}
            </a>
        @endforeach
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th width="40">No</th>
                    <th>Produk</th>
                    <th width="140">Kategori</th>
                    <th>Ukuran, Harga & Stok</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>

            @forelse($products as $index => $product)
                <tr>
                    {{-- NO --}}
                    <td class="text-center">
                        {{ $index + $products->firstItem() }}
                    </td>

                    {{-- PRODUK --}}
                    <td>
                        <div class="d-flex gap-2">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     width="60" height="60"
                                     style="object-fit:cover;border-radius:4px;">
                            @endif
                            <div>
                                <div class="fw-semibold">
                                    {{ $product->nama_produk }}
                                </div>
                                <small class="text-muted">
                                    {{ $product->jenis }}
                                </small>
                            </div>
                        </div>
                    </td>

                    {{-- KATEGORI --}}
                    <td class="text-center">
                        {{ $product->kategori->nama_kategori ?? '-' }}
                    </td>

                    {{-- UKURAN --}}
                    <td>
                        @if($product->sizes->count())
                            @foreach($product->sizes as $size)
                                <div class="d-flex justify-content-between border rounded px-2 py-1 mb-1">
                                    <div>
                                        <strong>{{ $size->size }}</strong>
                                    </div>
                                    <div>
                                        Rp {{ number_format($size->harga,0,',','.') }}
                                    </div>
                                    <div>
                                        Stok:
                                        <span class="{{ $size->stok <= 5 ? 'text-danger fw-bold' : '' }}">
                                            {{ $size->stok }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada ukuran</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="text-center">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="btn btn-sm btn-primary mb-1 w-100">
                            Edit
                        </a>

                        <a href="{{ route('admin.products.reviews', $product->id) }}"
                           class="btn btn-sm btn-outline-secondary mb-1 w-100">
                            Review
                        </a>

                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger w-100">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada produk
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>

</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formKategori">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_kategori"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi (opsional)</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>

                    <div id="kategoriError"
                         class="text-danger d-none"></div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-primary"
                        id="btnSimpanKategori">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT KATEGORI --}}
<script>
document.getElementById('btnSimpanKategori').addEventListener('click', function () {

    const form = document.getElementById('formKategori');
    const formData = new FormData(form);

    fetch("{{ route('admin.categories.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.errors) {
            const err = document.getElementById('kategoriError');
            err.classList.remove('d-none');
            err.innerText = data.errors.nama_kategori[0];
        } else {
            location.reload();
        }
    });
});
</script>
@endsection
