@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4>Beri Review untuk Produk</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $produk->nama_produk }}</h5>
            @if ($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="img-fluid mb-3" style="max-width: 200px;">
            @endif
            <p class="card-text">Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('review.store') }}" method="POST">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        <input type="hidden" name="pesanan_id" value="{{ request('pesanan_id') }}">
        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-5)</label>
            <select name="rating" id="rating" class="form-select" required>
                <option value="">Pilih rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Komentar</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required></textarea>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('pesanan.history') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Kirim Review</button>
        </div>
    </form>
</div>
@endsection
