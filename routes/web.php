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

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProdukController::class, 'showToUser'])->name('user.products');
Route::get('/produk/{id}', [ProdukController::class, 'showToUserDetail'])->name('produk.detail');
Route::get('/produk', [ProdukController::class, 'showToUser'])->name('produk.index');

Route::get('/paket', [PaketUserController::class, 'index'])->name('paket.index');
Route::get('/paket/{paket}', [PaketUserController::class, 'show'])->name('paket.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/paket', [CartController::class, 'storePaket'])->name('cart.store.paket');
    Route::post('/cart/paket/{paket}', [CartController::class, 'addPaket'])->name('cart.paket.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

Route::middleware('auth')->group(function () {
    Route::get('/alamat', [AlamatController::class, 'index'])->name('alamat.index');
    Route::post('/alamat', [AlamatController::class, 'store'])->name('alamat.store');
    Route::put('/alamat/{alamat}', [AlamatController::class, 'update'])->name('alamat.update');
    Route::post('/alamat/{alamat}/default', [AlamatController::class, 'setDefault'])->name('alamat.default');
    Route::delete('/alamat/{alamat}', [AlamatController::class, 'destroy'])->name('alamat.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PesananController::class, 'checkoutForm'])->name('pesanan.checkoutForm');
    Route::post('/checkout', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/history', [PesananController::class, 'history'])->name('pesanan.history');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{id}/update-status', [PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{pesanan}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/send', [ChatController::class, 'send'])->name('chat.send');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/refund/create/{pesanan_id}', [RefundController::class, 'create'])->name('refund.create');
    Route::post('/refund/{pesanan_id}', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/refund/{id}', [RefundController::class, 'show'])->name('refund.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/produk/{produk}/review', [ReviewController::class, 'form'])->name('review.form');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/user/notifications', [UserNotificationController::class, 'index'])->name('user.notifications.index');
    Route::get('/user/notifications/read/{id}', [UserNotificationController::class, 'markAsRead'])->name('user.notifications.read');
    Route::post('/user/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('user.notifications.readAll');
});

Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function() {
        return view('admin.dashboard'); // tampilan dashboard admin
    })->name('admin.dashboard');
});
Route::get('/admin/dashboard', [ProdukController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/products', [ProdukController::class, 'index'])->name('products.index');
Route::get('/admin/products/create', [ProdukController::class, 'create'])->name('products.create');
Route::post('/admin/products', [ProdukController::class, 'createProduk'])->name('products.store');
Route::get('/admin/products/{product}/edit', [ProdukController::class, 'edit'])->name('products.edit');
Route::put('/admin/products/{product}', [ProdukController::class, 'update'])->name('products.update');
Route::delete('/admin/products/{product}', [ProdukController::class, 'destroy'])->name('products.destroy');
Route::get('/admin/products/{id}/reviews', [ProdukController::class, 'showReviews'])->name('products.reviews');
Route::delete('/admin/reviews/{id}', [ProdukController::class, 'destroyReview'])->name('reviews.destroy');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/stok', [StokController::class, 'index'])->name('admin.stok.index');
    Route::post('/stok/update', [StokController::class, 'update'])->name('admin.stok.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/harga', [HargaController::class, 'index'])->name('admin.harga.index');
    Route::post('/harga/update', [HargaController::class, 'update'])->name('admin.harga.update');
});

Route::get('/admin/histori-harga', [PriceHistoryController::class, 'index'])->name('admin.price_histories.index');

Route::get('/admin/categories', [KategoriController::class, 'index'])->name('categories.index');
Route::post('/admin/categories/store', [KategoriController::class, 'store'])->name('categories.store');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/paket')
    ->name('admin.paket.')
    ->group(function () {
    Route::get('/', [PaketController::class, 'index'])->name('index');
    Route::get('/create', [PaketController::class, 'create'])->name('create');
    Route::post('/', [PaketController::class, 'store'])->name('store');
    Route::get('/{paket}/edit', [PaketController::class, 'edit'])->name('edit');
    Route::put('/{paket}', [PaketController::class, 'update'])->name('update');
    Route::delete('/{paket}', [PaketController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/chat/{chat}', [ChatController::class, 'adminShow'])->name('admin.chat.show');
});


Route::get('/admin/promos', [PromoController::class, 'index'])->name('promos.index');
Route::get('/admin/promos/create', [PromoController::class, 'create'])->name('promos.create');
Route::post('/admin/promos', [PromoController::class, 'store'])->name('promos.store');
Route::get('/admin/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
Route::put('/admin/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
Route::delete('/admin/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
Route::get('/admin/pengguna/{id}/riwayat', [PenggunaController::class, 'riwayat'])->name('pengguna.riwayat');

Route::get('admin/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
Route::get('admin/pesanan/{id}', [AdminPesananController::class, 'show'])->name('admin.pesanan.show');
Route::post('admin/pesanan/{id}/update-status', [AdminPesananController::class, 'updateStatus'])->name('admin.pesanan.updateStatus');

Route::get('admin/refund', [RefundController::class, 'adminIndex'])->name('refund.index');
Route::get('admin/refund/{id}', [RefundController::class, 'adminShow'])->name('admin.refund.show');
Route::put('admin/refund/{id}', [RefundController::class, 'adminUpdate'])->name('admin.refund.update');

// Menampilkan daftar notifikasi admin
Route::get('admin/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');

// Menandai satu notifikasi sebagai dibaca (klik dari dropdown notifikasi)
Route::get('admin/notifications/read/{id}', [AdminNotificationController::class, 'markAsRead'])->name('admin.notifications.read');

// Menandai semua notifikasi sebagai dibaca (dari halaman daftar notifikasi)
Route::post('admin/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('admin.notifications.readAll');

Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/admin/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.pdf');
Route::get('/admin/laporan/detail', [LaporanController::class, 'detail'])->name('admin.laporan.detail');
Route::get('/admin/laporan/detail/pdf', [LaporanController::class, 'detailPdf'])->name('admin.laporan.detail.pdf');
Route::get('/admin/laporan/produk-terlaris',[LaporanController::class, 'produkTerlaris'])->name('admin.laporan.produk_terlaris');
Route::get('/admin/laporan/produk-terlaris/pdf',[LaporanController::class, 'produkTerlarisPdf'])->name('admin.laporan.produk_terlaris_pdf');
Route::get('/admin/laporan/paket-terlaris',[LaporanController::class, 'paketTerlaris'])->name('admin.laporan.paket_terlaris');
Route::get('/admin/laporan/paket-terlaris/pdf',[LaporanController::class, 'paketTerlarisPdf'])->name('admin.laporan.paket_terlaris_pdf');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/akun')
    ->name('admin.user.')
    ->group(function () {

    Route::get('/admin', [ManagementController::class, 'admin'])
        ->name('admin');

    Route::get('/kurir', [ManagementController::class, 'kurir'])
        ->name('kurir');

    Route::post('/store', [ManagementController::class, 'store'])
        ->name('store');
});


Route::middleware(['auth', 'role:kurir'])
    ->prefix('kurir')
    ->name('kurir.')
    ->group(function () {

    Route::get('/dashboard', [KurirController::class, 'dashboard'])->name('dashboard');
    Route::get('/pesanan', [KurirController::class, 'pesanan'])->name('pesanan');
    Route::get('/profil', [KurirController::class, 'profil'])->name('profil');
    Route::post('/pesanan/{id}/selesai', [KurirController::class, 'selesai'])->name('pesanan.selesai');
    Route::get('/riwayat', [KurirController::class, 'riwayat'])->name('riwayat');
});
Route::middleware(['auth', 'role:kurir'])->group(function () {
    Route::get('/kurir/pesanan/{tugas}', [KurirController::class, 'detail'])->name('kurir.pesanan.detail');
    Route::post('/pesanan/{id}/selesai', [KurirController::class, 'selesai'])->name('kurir.selesai');
});

Route::middleware(['auth', 'role:kurir'])->group(function () {
    Route::get('/kurir/chat/{chat}', [ChatController::class, 'kurirShow'])->name('kurir.chat.show');
});

Route::middleware(['auth'])->group(function () {

    Route::put('/kurir/profile', [KurirController::class, 'updateProfile'])
        ->name('kurir.profile.update');

    Route::put('/kurir/password', [KurirController::class, 'updatePassword'])
        ->name('kurir.password.update');

});

