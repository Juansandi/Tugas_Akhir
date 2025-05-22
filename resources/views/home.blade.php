@extends('layouts.app')

@section('content')
<div class="container-fluid text-center text-white py-5" 
     style="background: url('https://rsud.bulelengkab.go.id/uploads/konten/32_manfaat-sayur-untuk-anak-menunjang-tumbuh-kembang-yang-optimal.jpg') center center / cover no-repeat; height: 600px;">
    <div class="py-5" style="background-color: rgba(0,0,0,0.5);">
        <h1 class="display-4 fw-bold">Belanja Beras & Sayur Berkualitas Tanpa Keluar Rumah</h1>
        <p class="lead">Dapatkan produk segar langsung dari petani</p>
        <a href="#products" class="btn btn-light mt-3">Belanja Sekarang</a>
    </div>
</div>


{{-- Produk Pilihan Hari Ini --}}
<div class="container py-5" id="products">
    <h2 class="text-center mb-4">Produk Pilihan Hari Ini</h2>
    @if($featuredProducts->count() > 0)
        <div class="row justify-content-center">
            @foreach($featuredProducts as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->nama_produk }}</h5>
                        <p class="card-text text-muted mb-1">{{ Str::limit($product->deskripsi, 50) }}</p>
                        <p class="card-text"><strong>Rp {{ number_format($product->harga, 0, ',', '.') }}</strong></p>
                        <p class="card-text"><small class="text-muted">Stok: {{ $product->stok }}</small></p>
                        
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('user.products') }}" class="btn btn-outline-primary">Lihat Semua Produk</a>
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">Belum ada produk tersedia.</p>
            <a href="{{ route('user.products') }}" class="btn btn-outline-primary">Cek Produk Lainnya</a>
        </div>
    @endif
</div>

{{-- Kategori Produk --}}
<div class="container py-5">
    <h2 class="text-center mb-4">Lihat Kategori Produk Kami</h2>
    <p class="text-center">Jelajahi pilihan kategori produk kami dari beras, sayur, hingga umbi-umbian</p>
    <div class="row justify-content-center">
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcwmiQUfzNzvfwnovFZk0Lzw4ZriBl75108w&s" class="card-img-top" alt="Beras">
                <div class="card-body text-center">
                    <h5 class="card-title">Beras</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <img src="https://akcdn.detik.net.id/visual/2021/06/08/ilustrasi-sayur_169.jpeg?w=1200" class="card-img-top" alt="Sayur-Sayuran">
                <div class="card-body text-center">
                    <h5 class="card-title">Sayur-Sayuran</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow">
                <img src="https://d1vbn70lmn1nqe.cloudfront.net/prod/wp-content/uploads/2022/08/10085013/Ini-X-Jenis-Umbi-umbian-dan-Manfaatnya-untuk-Kesehatan.jpg" class="card-img-top" alt="Serat & Umbi">
                <div class="card-body text-center">
                    <h5 class="card-title">Serat & Umbi</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
