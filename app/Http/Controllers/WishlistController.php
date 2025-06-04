<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.wishlist.index', compact('wishlistItems'));
    }

    public function store($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $productId)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => Auth::id(),
                'produk_id' => $productId
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke wishlist!');
    }

    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('produk_id', $productId)
            ->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
