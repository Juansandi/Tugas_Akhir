<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductSize;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with([
            'produk',
            'size',
            'paket.detailPakets.size',
            'paket.detailPakets.produk'
        ])
        ->where('user_id', Auth::id())
        ->get();

        $stokError = false;

        foreach ($cartItems as $item) {

            if ($item->type === 'produk') {
                if (!$item->size || $item->size->stok < $item->quantity) {
                    $stokError = true;
                }
            }

            if ($item->type === 'paket') {
                foreach ($item->paket->detailPakets as $detail) {
                    if ($detail->size && $detail->size->stok < ($detail->quantity * $item->quantity)) {
                        $stokError = true;
                    }
                }
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

        $cart = Cart::where('user_id', Auth::id())
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
                'user_id'         => Auth::id(), 
                'produk_id'       => $request->produk_id,
                'product_size_id' => $request->size_id,
                'quantity'        => $request->quantity,
                'type'            => 'produk'
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

    public function storePaket(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id',
        ]);

        $paket = Paket::with('detailPakets.size')->findOrFail($request->paket_id);

        // paket nonaktif
        if ($paket->status !== 'aktif') {
            return back()->with('error', 'Paket tidak tersedia.');
        }

        // cek stok tiap produk dalam paket
        foreach ($paket->detailPakets as $item) {
            if ($item->size && $item->size->stok < $item->quantity) {
                return back()->with(
                    'error',
                    'Stok produk dalam paket tidak mencukupi.'
                );
            }
        }

        // cek apakah paket sudah ada di cart
        $cart = Cart::where('user_id', Auth::id())
            ->where('paket_id', $paket->id)
            ->where('type', 'paket')
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'paket_id' => $paket->id,
                'quantity' => 1,
                'type' => 'paket'
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Paket berhasil ditambahkan ke keranjang.');
    }

    public function addPaket(Request $request, $paketId)
    {
        $paket = Paket::with('detailPakets')->findOrFail($paketId);

        // Cek stok semua isi paket
        foreach ($paket->detailPakets as $item) {
            if ($item->size && $item->size->stok < $item->quantity) {
                return back()->with('error',
                    'Stok paket tidak mencukupi ('.$item->produk->nama_produk.')'
                );
            }
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->where('type', 'paket')
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'paket_id' => $paketId,
                'quantity' => 1,
                'type' => 'paket'
            ]);
        }

        return back()->with('success', 'Paket ditambahkan ke keranjang');
    }
}
