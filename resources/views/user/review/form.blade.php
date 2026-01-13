@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 720px">

    <h4 class="mb-4">✍️ Beri Review Produk</h4>

    {{-- CARD PRODUK --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex gap-3 align-items-center">

            {{-- GAMBAR --}}
            @if ($detail->produk->image)
                <img src="{{ asset('storage/' . $detail->produk->image) }}"
                     alt="{{ $detail->produk->nama_produk }}"
                     style="width:120px;height:120px;object-fit:cover;border-radius:12px">
            @endif

            {{-- INFO --}}
            <div>
                <h5 class="mb-1">{{ $detail->produk->nama_produk }}</h5>

                @if($detail->size)
                    <small class="text-muted">
                        Ukuran: {{ $detail->size->size }}
                    </small><br>
                @endif

                <p class="mb-1 fw-semibold">
                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                </p>

                <span class="badge bg-success">Produk yang dibeli</span>
            </div>

        </div>
    </div>

    {{-- FORM REVIEW --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('review.store') }}" method="POST">
                @csrf

                <input type="hidden" name="produk_id" value="{{ $detail->produk_id }}">
                <input type="hidden" name="pesanan_id" value="{{ $pesananId }}">

                {{-- RATING --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Rating</label>
                    <div class="d-flex gap-2">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio"
                                   class="btn-check"
                                   name="rating"
                                   id="rating{{ $i }}"
                                   value="{{ $i }}"
                                   required>
                            <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                ⭐ {{ $i }}
                            </label>
                        @endfor
                    </div>
                </div>

                {{-- KOMENTAR --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Komentar</label>
                    <textarea name="comment"
                              class="form-control"
                              rows="4"
                              placeholder="Ceritakan pengalamanmu dengan produk ini..."
                              required></textarea>
                </div>

                {{-- AKSI --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('pesanan.show', $pesananId) }}"
                       class="btn btn-outline-secondary">
                        ← Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        Kirim Review
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
