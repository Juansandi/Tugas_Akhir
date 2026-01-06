<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Model\App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        // ===============================
        // PRODUK PILIHAN (BERDASARKAN SIZE)
        // ===============================
        $featuredProducts = Produk::with('sizes')
            ->whereHas('sizes', function ($q) {
                $q->where('stok', '>', 0);
            })
            ->latest()
            ->take(4)
            ->get();

         $produkTerlaris = Produk::with('sizes')
        ->whereHas('detailPesanan')
        ->withSum('detailPesanan as total_terjual', 'quantity')
        ->orderByDesc('total_terjual')
        ->take(4)
        ->get();

        // ===============================
        // KATEGORI
        // ===============================
        $categories = Kategori::withCount('products')
            ->orderByDesc('products_count')
            ->take(4)
            ->get();

        // ===============================
        // PRODUK REKOMENDASI
        // ===============================
        $recommendedProducts = collect();

        if (Auth::check()) {
            $userId = Auth::id();

            $pernahPesan = Pesanan::where('user_id', $userId)->exists();

            if ($pernahPesan) {
                $kategoriIds = Pesanan::where('user_id', $userId)
                    ->with('detail.produk')
                    ->get()
                    ->pluck('detail.*.produk.kategori_id')
                    ->flatten()
                    ->unique()
                    ->toArray();

                $recommendedProducts = Produk::with('sizes')
                    ->whereIn('kategori_id', $kategoriIds)
                    ->whereHas('sizes', function ($q) {
                        $q->where('stok', '>', 0);
                    })
                    ->take(4)
                    ->get();
            } else {
                // customer baru
                $recommendedProducts = Produk::with('sizes')
                    ->whereHas('sizes', function ($q) {
                        $q->where('stok', '>', 0);
                    })
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            }
        }

        // ===============================
        // PESANAN TERAKHIR
        // ===============================
        $lastOrder = Auth::check()
            ? Pesanan::where('user_id', Auth::id())->latest()->first()
            : null;

        return view('home', compact(
            'featuredProducts',
            'produkTerlaris',
            'recommendedProducts',
            'categories',
            'lastOrder'
        ));
    }
}
