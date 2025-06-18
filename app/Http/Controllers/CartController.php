<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('produk')->where('user_id', Auth::id())->get();
        return view('user.cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $produkId = $request->produk_id;
        $qty = $request->quantity;

        $cart = Cart::where('user_id', $userId)->where('produk_id', $produkId)->first();

        if ($cart) {
            // Produk sudah ada di cart, increment quantity
            $cart->quantity += $qty;
            $cart->save();
        } else {
            // Produk belum ada, buat baru dengan quantity sesuai request
            Cart::create([
                'user_id' => $userId,
                'produk_id' => $produkId,
                'quantity' => $qty,
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $total = $cartItem->produk->harga * $cartItem->quantity;

        return response()->json([
            'success' => true,
            'new_total' => number_format($total, 0, ',', '.'),
        ]);
    }

    public function destroy($id)
    {
        $item = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $item->delete();

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

}
