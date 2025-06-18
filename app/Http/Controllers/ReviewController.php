<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Review;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Tampilkan form review untuk produk tertentu
    public function form($produkId)
    {
        $produk = Produk::findOrFail($produkId);
        return view('user.review.form', compact('produk'));
    }

    // Simpan review
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('pesanan.history')->with('success', 'Review berhasil dikirim!');
    }

}
