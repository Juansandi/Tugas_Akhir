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
            'pesanan_id' => 'required|exists:pesanans,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        // Cek apakah user sudah pernah review produk di pesanan itu
        $exists = Review::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('pesanan_id', $request->pesanan_id)
            ->exists();

        if ($exists) {
            return redirect()->route('pesanan.show', $request->pesanan_id)
                ->with('error', 'Anda sudah memberikan review untuk produk ini di pesanan tersebut.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'pesanan_id' => $request->pesanan_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('pesanan.show', $request->pesanan_id)->with('success', 'Review berhasil dikirim!');
    }

}
