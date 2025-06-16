@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2>Ulasan untuk Produk: {{ $product->nama_produk }}</h2>

    <p class="text-muted">Total Ulasan: {{ $product->reviews->count() }}</p>

    @forelse($product->reviews as $review)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $review->user->username ?? 'Anonim' }}</strong>
                        <small class="text-muted ms-2">{{ $review->created_at->format('d M Y') }}</small>
                        <div class="text-warning">
                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </div>
                        <p class="mb-0">{{ $review->comment }}</p>
                    </div>
                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
    @endforelse

    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Produk</a>
</div>
@endsection
