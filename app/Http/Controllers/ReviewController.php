<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Cek apakah user sudah memberi ulasan sebelumnya
        $existing = Review::where('user_id', $userId)
                        ->where('produk_id', $request->produk_id)
                        ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberi ulasan untuk produk ini.');
        }

        Review::create([
            'user_id' => $userId,
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan.');
    }
}
