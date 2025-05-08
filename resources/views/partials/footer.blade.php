<footer class="bg-dark text-white mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <h5>TokoSayur</h5>
                <p>Belanja beras dan sayur segar langsung dari petani.</p>
                <p>Email: contact@tokosayur.com</p>
            </div>
            <div class="col-md-6">
                <h5>Navigasi</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    <li><a href="#" class="text-white">Produk</a></li>
                    <li><a href="#" class="text-white">Kategori</a></li>
                    <li><a href="#" class="text-white">Kontak Kami</a></li>
                </ul>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center">
            &copy; {{ date('Y') }} TokoSayur. All Rights Reserved.
        </div>
    </div>
</footer>
