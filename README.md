<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



LINK USE CASE & ERD:
https://drive.google.com/file/d/1q_0G0wMnaKCzcP8aUfLlKKwnWv2X1M3h/view?usp=sharing

LINK FIGMA:
https://www.figma.com/design/Bcyzg2q03b4VTgxCUaZjX1/Desai-Projek?node-id=0-1&t=JM2c1IEHsBQTI6fo-1

Fitur-fitur Website Penjualan Beras & Sayur:

a. Fitur Pengguna: 
    1. Pilihan Produk 
    • Kategori Beras:  - 
        Jenis: Beras Putih, Beras Merah, Beras Organik - - 
        Ukuran: 5 kg, 10 kg, 25 kg. 
        Varietas: Pandan Wangi, IR64, Rojolele, dil 
    • Kategori Sayur: - 
        Sayuran Hijau. Bayam, Kangkung, Sawi, dll. - - 
        Sayuran Buah: Tomat, Cabal, Terong, dll. 
        Sayuran Umbi: Kentang, Wortel, Singkong, dll. 
    2. Pencarian dan Filter yang Lebih Detail 
        • Filter berdasarkan harga, merek, jenis, atau ketersediaan produk. 
        • Pencarian menggunakan kata kunci yang lebih fleksibel. 
    3. Keranjang Belanja  
    Memungkinkan pengguna untuk: 
        • Menambahkan produk ke keranjang. 
        • Mengubah jumlah atau menghapus produk. 
        • Melihat total harga sementara. 
    4.  Pembayaran 
        • Pilihan Metode Pembayaran: - 
        Transfer Bank - - 
        E-Wallet (OVO, GoPay, Dana) 
        COD (Cash on Delivery) 
        • Konfirmasi pembayaran dan validasi otomatis (jika online). 
    5. Lacak pesanan 
        • Lihat status pesanan: Belum Dibayar, Diproses, Dikirim, Selesai. 
        • Pelacakan pengiriman dengan nomor resi (jika dikirim melalui kurir). 
    6. Riwayat Pesanan 
        • Tampilkan daftar pesanan sebelumnya dengan detail: - Produk yang dibeli. - Total harga. - Tanggal pembelian. 
    7. Ulasan Produk 
        • Pengguna dapat memberikan rating (1–5 bintang)  
        • Pengguna dapat menulis ulasan untuk produk yang telah dibeli. 
    8. Wishlist Produk  
        Menyimpan produk favorit untuk pembelian di masa mendatang. 
    9.  Notifikasi 
        • Pesanan diproses atau dikirim. 
        • Promo produk tertentu. 
        • Informasi stok produk favorit kembali tersedia. 
        • Pemberitahuan untuk setiap tahap proses pengembalian produk atau refund. 
    10. Sistem Poin 
        • Pengguna mendapatkan poin untuk setiap pembelian. 
        • Poin bisa ditukar dengan diskon atau produk tertentu. 
    11. Pengembalian Produk (Return/Refund) 
        • Pengguna dapat mengajukan pengembalian langsung dari riwayat pesanan. 
        • Pilihan alasan pengembalian: - 
        Produk rusak. - - 
        Produk salah kirim. 
        Produk tidak sesuai deskripsi. 
        Lainnya (dengan kolom input tambahan). 
        • Unggah bukti pendukung (foto produk yang bermasalah). 
        • Pilihan cara pengembalian dana jika refund diterima: - Transfer Bank, E-Wallet (OVO, GoPay, Dana). 
    
b. Fitur Admin: 
    1. Manajemen Produk 
        • Tambah/Edit/Hapus produk beras dan sayur, 
        • Kelola informasi produk - Nama, Deskripsi, Kategori, Harga, Stok 
        • Upload gambar produk 
    2. Manajemen Pesanan 
        • Lihat daftar pesanan pengguna. 
        • Update status pesanan: Belum Dibayar, Diproses, Dikirim, Selesai. 
    3.  Manajemen Stok 
        • Monitor stok produk. 
        • Notifikasi stok hampir habis. 
    4. Manajemen Promo 
        • Tambah/Edit promo diskon produk tertentu 
        • Atur durasi promo (contoh: diskon akhir bulan). 
    5. Laporan Penjualan 
        • Statistik harian, mingguan, dan bulanan. 
        • Grafik penjualan berdasarkan kategori produk (beras atau sayur). 
        • Produk terlaris. 
    6.  Manajemen Pengguna 
        • Lihat daftar pengguna. 
        • Akses riwayat pesanan pengguna. 
    7. Pengelolaan Ulasan Pengguna 
        • Tinjau ulasan dan rating produk dari pengguna. 
        • Hapus ulasan tidak sesuai (opsional). 
    8. Manajemen Pengembalian Barang (Return/Refund) 
        • Kelola pengajuan pengembalian atau pengembalian dana (refund). 
        • Verifikasi alasan pengembalian barang (produk rusak, salah kirim, dll). 
        • Integrasi dengan sistem stok untuk menyesuaikan barang yang dikembalikan. 
        • Kelola Pengembalian Dana. 
    9. Notifikasi Admin 
        • Pesanan baru masuk. 
        • Stok produk tertentu hampir habis. 
        • Notifikasi saat ada pengajuan pengembalian baru.
