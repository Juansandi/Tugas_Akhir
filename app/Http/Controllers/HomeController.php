<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Produk::where('stok', '>', 0)
            ->latest()
            ->take(4)
            ->get();
        $categories = Kategori::withCount('products')
            ->take(3) 
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
