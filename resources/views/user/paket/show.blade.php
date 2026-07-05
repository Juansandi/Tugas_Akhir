@extends('layouts.app')

@section('content')

<div class="container py-5">
<div class="row g-4">
    {{-- ================= GAMBAR ================= --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center justify-content-center p-4">
                <img
                    src="{{ $paket->image
                        ? asset('storage/'.$paket->image)
                        : 'https://via.placeholder.com/700x500?text=Paket' }}"
                    class="img-fluid rounded-4"
                    style="max-height:520px;object-fit:contain;">
            </div>
        </div>
    </div>

    {{-- ================= INFORMASI ================= --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <span class="badge bg-success-subtle text-success mb-3">
                    <i class="bi bi-box-seam me-1"></i>
                    Paket Produk
                </span>

                <h1 class="fw-bold mb-2">
                    {{ $paket->nama_paket }}
                </h1>

                <p class="text-muted mb-3">
                    {{ $paket->deskripsi }}
                </p>

                <small class="text-muted">
                    Harga Paket
                </small>

                <h2 class="text-success fw-bold mb-4">
                    Rp {{ number_format($paket->harga_paket,0,',','.') }}
                </h2>

                {{-- KEUNGGULAN --}}
                <div class="row g-3 mb-4">

                    <div class="col-6">
                        <div class="border rounded-3 p-3 h-100">
                            <i class="bi bi-piggy-bank text-success me-2"></i>
                            Lebih Hemat
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-3 h-100">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Produk Berkualitas
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-3 h-100">
                            <i class="bi bi-truck text-success me-2"></i>
                            Kurir Internal
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="border rounded-3 p-3 h-100">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            Transaksi Aman
                        </div>
                    </div>
                </div>

                {{-- ISI PAKET --}}
                <h5 class="fw-bold mb-3">
                    Isi Paket
                </h5>

                <div class="border rounded-3 mb-4">
                    @foreach($paket->detailPakets as $item)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <strong>
                                {{ $item->produk->nama_produk }}
                            </strong>
                            @if($item->size)
                            <div class="small text-muted">
                                Ukuran {{ $item->size->size }}
                            </div>
                            @endif
                        </div>
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            x{{ $item->quantity }}
                        </span>
                    </div>
                    @endforeach
                </div>

                {{-- BUTTON --}}
                <form action="{{ route('cart.store.paket') }}" method="POST">
                    @csrf
                    <input type="hidden"
                           name="paket_id"
                           value="{{ $paket->id }}">
                    <button class="btn btn-success btn-lg w-100">
                        <i class="bi bi-cart-plus me-2"></i>
                        Tambah Paket ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection