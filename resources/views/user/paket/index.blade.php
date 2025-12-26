@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Paket Hemat</h3>

    <div class="row">
        @foreach($pakets as $paket)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($paket->image)
                    <img src="{{ asset('storage/'.$paket->image) }}"
                         class="card-img-top"
                         style="height:200px;object-fit:cover">
                @endif

                <div class="card-body d-flex flex-column">
                    <h5>{{ $paket->nama_paket }}</h5>

                    <p class="text-muted small">
                        {{ Str::limit($paket->deskripsi, 80) }}
                    </p>

                    <strong class="mb-2 text-success">
                        Rp {{ number_format($paket->harga_paket,0,',','.') }}
                    </strong>

                    <a href="{{ route('paket.show', $paket->id) }}"
                       class="btn btn-outline-primary mt-auto">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
