@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="mb-4">
        Semua Ulasan — {{ $product->nama_produk }}
    </h4>

    @foreach($product->reviews->sortByDesc('created_at') as $review)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <strong>{{ $review->user->username ?? 'Anonim' }}</strong>
                <div class="text-warning mb-1">
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                </div>
                <p class="mb-0">{{ $review->comment }}</p>
            </div>
        </div>
    @endforeach

</div>
@endsection
