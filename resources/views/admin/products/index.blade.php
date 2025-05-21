@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Product Management</h2>
        <a href="{{ route('products.create') }}" class="btn btn-dark">Tambah Produk</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Merk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + $products->firstItem() }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="100" alt="{{ $product->nama_produk }}">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $product->nama_produk }}</td>
                        <td>{{ $product->jenis }}</td>
                        <td>{{ $product->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->stok }}</td>
                        <td>{{ Str::limit($product->deskripsi, 20) }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="text-primary">Edit</a> |
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
