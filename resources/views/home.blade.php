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
    <div class="row justify-content-center">
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow">
                <img src="https://img.id.my-best.com/product_images/e24d156116ac2427abefe9ac214e2250.jpeg?ixlib=rails-4.3.1&q=70&lossless=0&w=800&h=800&fit=clip&s=a055992ba7c8bdb30e059241c440930d" class="card-img-top" alt="Beras Merah">
                <div class="card-body">
                    <h5 class="card-title">Beras Merah</h5>
                    <p class="card-text">Rp 50.000</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow">
                <img src="https://cdn1-production-images-kly.akamaized.net/B_BCrWc2E2aZj_dFoTKcz5O6oB4=/1200x1200/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/4021819/original/011012800_1652430132-grain-that-is-cup-wooden-floor.jpg" class="card-img-top" alt="Beras Putih Premium">
                <div class="card-body">
                    <h5 class="card-title">Beras Putih Premium</h5>
                    <p class="card-text">Rp 60.000</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow">
                <img src="https://i0.wp.com/raisa.aeonstore.id/wp-content/uploads/2023/04/801898.jpeg?fit=500%2C500&ssl=1" class="card-img-top" alt="Sawi Putih">
                <div class="card-body">
                    <h5 class="card-title">Sawi Putih</h5>
                    <p class="card-text">Rp 5.000</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow">
                <img src="https://cdn.rri.co.id/berita/Lhokseumawe/o/1715440226853-1000038210/fqm7dorm11p3y0j.jpeg" class="card-img-top" alt="Sayur Bayam">
                <div class="card-body">
                    <h5 class="card-title">Sayur Bayam</h5>
                    <p class="card-text">Rp 3.000</p>
                </div>
            </div>
        </div>
    </div>
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
