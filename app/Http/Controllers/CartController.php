<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductSize;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['produk', 'size'])
            ->where('user_id', Auth::id())
            ->get();

        $stokError = false;

        foreach ($cartItems as $item) {
            if ($item->size->stok < $item->quantity) {
                $stokError = true;
            }
        }

        return view('user.cart.index', compact('cartItems', 'stokError'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'size_id'   => 'required|exists:product_sizes,id',
            'quantity'  => 'required|integer|min:1',
        ]);

        $size = ProductSize::findOrFail($request->size_id);

        if ($size->stok == 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        if ($request->quantity > $size->stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $cart = Cart::where('user_id', Auth::id()) // 🔥 FIX
            ->where('produk_id', $request->produk_id)
            ->where('product_size_id', $request->size_id)
            ->first();

        if ($cart) {
            $newQty = $cart->quantity + $request->quantity;

            if ($newQty > $size->stok) {
                return back()->with('error', 'Jumlah di keranjang melebihi stok.');
            }

            $cart->update([
                'quantity' => $newQty
            ]);
        } else {
            Cart::create([
                'user_id'         => Auth::id(), // 🔥 FIX
                'produk_id'       => $request->produk_id,
                'product_size_id' => $request->size_id,
                'quantity'        => $request->quantity,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with('size')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        // melebihi stok
        if ($request->quantity > $cartItem->size->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok tersedia'
            ], 422);
        }

        // update qty
        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'quantity' => $cartItem->quantity
        ]);
    }


    public function destroy($id)
    {
        $item = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
