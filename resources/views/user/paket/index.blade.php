@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                Paket Produk
            </h2>
            <p class="text-muted mb-0">
                Pilihan paket kebutuhan pokok dengan harga lebih hemat dibandingkan membeli satuan.
            </p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($pakets as $paket)
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card package-card border-0 shadow-sm h-100">
                {{-- GAMBAR --}}
                <img
                    src="{{ $paket->image
                        ? asset('storage/'.$paket->image)
                        : 'https://via.placeholder.com/600x400?text=Paket' }}"
                    class="card-img-top"
                    alt="{{ $paket->nama_paket }}">

                <div class="card-body d-flex flex-column">
                    {{-- BADGE --}}
                    <span class="badge bg-success-subtle text-success mb-3 align-self-start">
                        <i class="bi bi-box-seam me-1"></i>
                        Paket Produk
                    </span>

                    {{-- NAMA --}}
                    <h4 class="fw-bold mb-2">
                        {{ $paket->nama_paket }}
                    </h4>

                    {{-- DESKRIPSI --}}
                    <p class="text-muted small mb-3">
                        {{ Str::limit($paket->deskripsi,90) }}
                    </p>

                    {{-- HARGA --}}
                    <small class="text-muted">
                        Harga Paket
                    </small>

                    <h3 class="fw-bold text-success mb-3">
                        Rp {{ number_format($paket->harga_paket,0,',','.') }}
                    </h3>

                    {{-- JUMLAH PRODUK (opsional jika relasi ada) --}}
                    @if(method_exists($paket,'details'))
                    <div class="mb-3">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-basket me-1"></i>
                            {{ $paket->details->count() }} Produk
                        </span>
                    </div>
                    @endif

                    {{-- BUTTON --}}
                    <div class="mt-auto">
                        <a href="{{ route('paket.show',$paket->id) }}"
                           class="btn btn-success w-100">
                            <i class="bi bi-box me-2"></i>
                            Lihat Isi Paket
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty

        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-box-seam display-3 text-muted"></i>

                <h4 class="mt-3">
                    Belum Ada Paket
                </h4>

                <p class="text-muted">
                    Saat ini belum tersedia paket produk.
                </p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>

.package-card{
    border-radius:18px;
    overflow:hidden;
    transition:.25s ease;
}

.package-card:hover{
    transform:translateY(-6px);
    box-shadow:0 1rem 2rem rgba(0,0,0,.12)!important;
}

.package-card img{
    height:230px;
    object-fit:cover;
}

.package-card .btn{
    border-radius:10px;
    padding:12px;
    font-weight:600;
}

.package-card h4{
    min-height:60px;
}

</style>
@endsection