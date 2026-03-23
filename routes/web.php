<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AdminPesananController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\HargaController;
use App\Http\Controllers\PaketUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\Admin\PriceHistoryController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/produk', [ProdukController::class, 'showToUser'])->name('produk.index');
Route::get('/produk/{id}', [ProdukController::class, 'showToUserDetail'])->name('produk.detail');
Route::get('/produk/{id}/reviews', [ProdukController::class, 'reviews'])->name('produk.reviews');

Route::get('/paket', [PaketUserController::class, 'index'])->name('paket.index');
Route::get('/paket/{paket}', [PaketUserController::class, 'show'])->name('paket.show');



Route::get('/products', [ProdukController::class, 'showToUser'])->name('user.products');



Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/paket', [CartController::class, 'storePaket'])->name('cart.store.paket');
    Route::post('/cart/paket/{paket}', [CartController::class, 'addPaket'])->name('cart.paket.store');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    Route::get('/alamat', [AlamatController::class, 'index'])->name('alamat.index');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{alamat}', [AlamatController::class, 'update'])->name('alamat.update');
    Route::post('/alamat/{alamat}/default', [AlamatController::class, 'setDefault'])->name('alamat.default');
    Route::delete('/alamat/{alamat}', [AlamatController::class, 'destroy'])->name('alamat.destroy');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // PESANAN
    Route::get('/checkout', [PesananController::class, 'checkoutForm'])->name('pesanan.checkoutForm');
    Route::post('/checkout', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/history', [PesananController::class, 'history'])->name('pesanan.history');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::get('/pesanan/{pesanan}/pembayaran', [PesananController::class, 'formPembayaran'])->name('pesanan.pembayaran');
    Route::post('/pesanan/{pesanan}/upload-bukti', [PesananController::class, 'uploadBukti'])->name('pesanan.uploadBukti');

    Route::get('/chat/{pesanan}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/send', [ChatController::class, 'send'])->name('chat.send');

    Route::get('/refund/create/{pesanan_id}', [RefundController::class, 'create'])->name('refund.create');
    Route::post('/refund/{pesanan_id}', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/refund/{id}', [RefundController::class, 'show'])->name('refund.show');

    Route::get('/produk/{produk}/review', [ReviewController::class, 'form'])->name('review.form');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

    Route::get('/user/notifications', [UserNotificationController::class, 'index'])->name('user.notifications.index');
    Route::get('/user/notifications/read/{id}', [UserNotificationController::class, 'markAsRead'])->name('user.notifications.read');
    Route::post('/user/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('user.notifications.readAll');
});


Route::prefix('admin')
    ->middleware(['auth','role:admin,super_admin'])
    ->name('admin.')
    ->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [ProdukController::class, 'dashboard'])->name('dashboard');

    // PRODUK
    Route::get('/products', [ProdukController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProdukController::class, 'create'])->name('products.create');
    Route::post('/products', [ProdukController::class, 'createProduk'])->name('products.store');
    Route::get('/products/{product}/edit', [ProdukController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProdukController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProdukController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{id}/reviews', [ProdukController::class, 'showReviews'])->name('products.reviews');
    Route::delete('/reviews/{id}', [ProdukController::class, 'destroyReview'])->name('reviews.destroy');

    // STOK & HARGA
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::post('/stok/update', [StokController::class, 'update'])->name('stok.update');

    Route::get('/harga', [HargaController::class, 'index'])->name('harga.index');
    Route::post('/harga/update', [HargaController::class, 'update'])->name('harga.update');

    // HISTORI HARGA
    Route::get('/histori-harga', [PriceHistoryController::class, 'index'])->name('price_histories.index');

    // KATEGORI
    Route::get('/categories', [KategoriController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [KategoriController::class, 'store'])->name('categories.store');

    // PAKET
    Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');
    Route::get('/paket/create', [PaketController::class, 'create'])->name('paket.create');
    Route::post('/paket', [PaketController::class, 'store'])->name('paket.store');
    Route::get('/paket/{paket}/edit', [PaketController::class, 'edit'])->name('paket.edit');
    Route::put('/paket/{paket}', [PaketController::class, 'update'])->name('paket.update');
    Route::delete('/paket/{paket}', [PaketController::class, 'destroy'])->name('paket.destroy');

    // CHAT
    Route::get('/chat/{chat}', [ChatController::class, 'adminShow'])->name('chat.show');

    // PROMO
    Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');
    Route::get('/promos/create', [PromoController::class, 'create'])->name('promos.create');
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::get('/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
    Route::put('/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
    Route::delete('/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

    // PENGGUNA
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/{id}/riwayat', [PenggunaController::class, 'riwayat'])->name('pengguna.riwayat');

    // PESANAN
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::post('/pesanan/{pesanan}/verifikasi', [AdminPesananController::class, 'verifikasiPembayaran'])->name('pesanan.verifikasi');

    // REFUND
    Route::get('/refund', [RefundController::class, 'adminIndex'])->name('refund.index');
    Route::get('/refund/{id}', [RefundController::class, 'adminShow'])->name('refund.show');
    Route::put('/refund/{id}', [RefundController::class, 'adminUpdate'])->name('refund.update');

    // NOTIF
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // LAPORAN
    Route::prefix('laporan')->group(function () {

        // LAPORAN UTAMA
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

        // DETAIL
        Route::get('/detail', [LaporanController::class, 'detail'])->name('laporan.detail');
        Route::get('/detail/pdf', [LaporanController::class, 'detailPdf'])->name('laporan.detail.pdf');

        // REFUND
        Route::get('/refund', [LaporanController::class, 'refund'])->name('laporan.refund');
        Route::get('/refund/pdf', [LaporanController::class, 'refundPdf'])->name('laporan.refund.pdf');

        // PRODUK TERLARIS
        Route::get('/produk-terlaris', [LaporanController::class, 'produkTerlaris'])->name('laporan.produk_terlaris');
        Route::get('/produk-terlaris/pdf', [LaporanController::class, 'produkTerlarisPdf'])->name('laporan.produk_terlaris_pdf');

        // PAKET TERLARIS
        Route::get('/paket-terlaris', [LaporanController::class, 'paketTerlaris'])->name('laporan.paket_terlaris');
        Route::get('/paket-terlaris/pdf', [LaporanController::class, 'paketTerlarisPdf'])->name('laporan.paket_terlaris_pdf');
    });

    // MANAJEMEN USER
    Route::prefix('akun')->name('user.')->group(function () {
        Route::get('/admin', [ManagementController::class, 'admin'])->name('admin');
        Route::get('/kurir', [ManagementController::class, 'kurir'])->name('kurir');
        Route::post('/store', [ManagementController::class, 'store'])->name('store');
        Route::post('/{id}/toggle', [ManagementController::class, 'toggleStatus'])->name('toggle');
    });

});

Route::prefix('kurir')
    ->middleware(['auth','role:kurir'])
    ->name('kurir.')
    ->group(function () {

    Route::get('/dashboard', [KurirController::class, 'dashboard'])->name('dashboard');
    Route::get('/pesanan', [KurirController::class, 'pesanan'])->name('pesanan');
    Route::get('/pesanan/{tugas}', [KurirController::class, 'detail'])->name('pesanan.detail');
    Route::post('/pesanan/{tugas}/kirim', [KurirController::class, 'kirim'])->name('kirim');

    Route::get('/riwayat', [KurirController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', [KurirController::class, 'profil'])->name('profil');

    Route::put('/profile', [KurirController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [KurirController::class, 'updatePassword'])->name('password.update');

    Route::get('/chat/{chat}', [ChatController::class, 'kurirShow'])->name('chat.show');

});


