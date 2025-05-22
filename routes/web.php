<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PromoController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [ProdukController::class, 'showToUser'])->name('user.products');


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


Route::get('/admin/products', [ProdukController::class, 'index'])->name('products.index');
Route::get('/admin/products/create', [ProdukController::class, 'create'])->name('products.create');
Route::post('/admin/products', [ProdukController::class, 'createProduk'])->name('products.store');
Route::get('/admin/products/{product}/edit', [ProdukController::class, 'edit'])->name('products.edit');
Route::put('/admin/products/{product}', [ProdukController::class, 'update'])->name('products.update');
Route::delete('/admin/products/{product}', [ProdukController::class, 'destroy'])->name('products.destroy');

Route::get('/admin/promos', [PromoController::class, 'index'])->name('promos.index');
Route::get('/admin/promos/create', [PromoController::class, 'create'])->name('promos.create');
Route::post('/admin/promos', [PromoController::class, 'store'])->name('promos.store');
Route::get('/admin/promos/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
Route::put('/admin/promos/{promo}', [PromoController::class, 'update'])->name('promos.update');
Route::delete('/admin/promos/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');

Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');

