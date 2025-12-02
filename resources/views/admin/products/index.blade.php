@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Produk</h2>

        <div class="d-flex gap-2">
            <a href="{{ route('products.create') }}" class="btn btn-dark me-0">Tambah Produk</a>
            <button class="btn btn-secondary ms-0" data-bs-toggle="modal" data-bs-target="#modalKategori">
                Tambah Kategori
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   <div class="accordion" id="kategoriAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kategoriList">
            Daftar Kategori
        </button>
        </h2>
        <div id="kategoriList" class="accordion-collapse collapse">
        <div class="accordion-body">
            @foreach($categories as $category)
                <div>{{ $category->nama_kategori }}</div>
            @endforeach
        </div>
        </div>
    </div>
    </div>
</br>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Merk</th>
                    <th>Kategori</th>
                    <th>Harga Utama</th>
                    <th>Stok</th>
                    <th>Harga Per Ukuran</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + $products->firstItem() }}</td>

                        {{-- Gambar Produk --}}
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     width="90" height="90" 
                                     style="object-fit: cover; border-radius: 6px;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>

                        {{-- Nama Produk --}}
                        <td>{{ $product->nama_produk }}</td>

                        {{-- Merk --}}
                        <td>{{ $product->jenis }}</td>

                        {{-- Kategori --}}
                        <td>{{ $product->kategori->nama_kategori ?? '-' }}</td>

                        {{-- Harga utama --}}
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>

                        {{-- Stok total --}}
                        <td>{{ $product->stok }}</td>

                        {{-- Harga Per Ukuran --}}
                        <td class="text-start">
                            @if ($product->sizes->count())
                                @foreach ($product->sizes as $size)
                                    <div class="mb-1">
                                        <strong>{{ $size->size }}:</strong>
                                        Rp {{ number_format($size->harga, 0, ',', '.') }}
                                        <span class="text-muted"> (stok: {{ $size->stok }})</span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">Tidak ada ukuran</span>
                            @endif
                        </td>

                        {{-- Deskripsi --}}
                        <td>{{ Str::limit($product->deskripsi, 30) }}</td>

                        {{-- Tombol Aksi --}}
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="btn btn-sm btn-primary mb-1">Edit</a>

                            <form action="{{ route('products.destroy', $product->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Hapus produk ini?')" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>

                        {{-- Review --}}
                        <td>
                            <a href="{{ route('products.reviews', $product->id) }}" 
                               class="btn btn-sm btn-outline-primary"
                               title="Lihat Ulasan">
                                <i class="bi bi-chat-left-text"></i>
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>

<!-- Modal Tambah Kategori -->
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
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" required>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi (opsional)</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>

                    <div id="kategoriError" class="text-danger d-none"></div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSimpanKategori">Simpan</button>
            </div>

        </div>
    </div>
</div>
<script>
document.getElementById('btnSimpanKategori').addEventListener('click', function () {

    let form = document.getElementById('formKategori');
    let formData = new FormData(form);

    fetch("{{ route('categories.store') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.errors) {
            document.getElementById('kategoriError').classList.remove('d-none');
            document.getElementById('kategoriError').innerText = data.errors.nama_kategori[0];
        } else {
            // sukses → tutup modal & reload
            location.reload();
        }
    });
});
</script>
@endsection


