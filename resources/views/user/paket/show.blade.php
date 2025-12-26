@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/'.$paket->image) }}"
                 class="img-fluid rounded shadow">
        </div>

        <div class="col-md-6">
            <h3>{{ $paket->nama_paket }}</h3>
            <p class="text-muted">{{ $paket->deskripsi }}</p>

            <h4 class="text-success">
                Rp {{ number_format($paket->harga_paket,0,',','.') }}
            </h4>

            <hr>

            <h6>Isi Paket:</h6>
            <ul class="list-group mb-3">
                @foreach($paket->detailPakets as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>
                            {{ $item->produk->nama_produk }}
                            @if($item->size)
                                ({{ $item->size->size }})
                            @endif
                        </span>
                        <strong>x{{ $item->quantity }}</strong>
                    </li>
                @endforeach
            </ul>

            <form action="{{ route('cart.store.paket') }}" method="POST">
                @csrf
                <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                <button class="btn btn-dark w-100">
                    Tambah Paket ke Keranjang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
