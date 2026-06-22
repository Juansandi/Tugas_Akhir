<footer class="bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row">
            <!-- Brand Description -->
            <div class="col-md-4 mb-4">
                <h4 class="fw-bold mb-3">Toko Bahan Pokok</h4>
                <p class="small">Sistem Informasi Pengelolaan Transaksi Bahan Pokok Berbasis Website yang memudahkan pelanggan melakukan pembelian kebutuhan pokok secara daring.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase">Navigasi</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Beranda</a></li>
                    <li><a href="{{ route('produk.index') }}" class="text-white text-decoration-none">Produk</a></li>
                    <li><a href="#" class="text-white text-decoration-none">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4 mb-4">
                <h5 class="text-uppercase">Kontak</h5>
                <p class="small mb-1"><i class="bi bi-envelope"></i> contact@freshgo.com</p>
                <p class="small mb-1"><i class="bi bi-telephone"></i> +62 812-3456-7890</p>
                <p class="small mb-2"><i class="bi bi-geo-alt"></i> Jl. Sawah No. 123, Sleman, Yogyakarta</p>

                <!-- Optional Social Media -->
                <div class="d-flex gap-2">
                    <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <hr class="bg-white">
        <div class="text-center small">
            &copy; {{ date('Y') }} FreshGO. All Rights Reserved.
        </div>
    </div>
</footer>
