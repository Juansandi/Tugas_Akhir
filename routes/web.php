<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;

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


Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.editPassword');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

});

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
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


Route::get('/admin/promos', [PromoController::class, 'index'])->name('promos.index');
Route::get('/admin/promos/create', [PromoController::class, 'create'])->name('promos.create');
Route::post('/admin/promos', [PromoController::class, 'store'])->name('promos.store');
Route::get('/admin/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
Route::put('/admin/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
Route::delete('/admin/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');

