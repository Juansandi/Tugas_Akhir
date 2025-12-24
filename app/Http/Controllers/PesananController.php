<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\Cart;
use App\Models\Promo;
use App\Models\AdminNotification;
use Carbon\Carbon;

class PesananController extends Controller
{

    public function checkoutForm()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
        ->with(['produk', 'size'])
        ->get();

        $alamat = $user->alamat ?? '-';

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->size->harga * $item->quantity;
        }

        $promos = Promo::where('mulai', '<=', now())
                        ->where('akhir', '>=', now())
                        ->get();

        return view('user.pesanan.checkout', compact('cartItems', 'alamat', 'subtotal', 'promos'));
    }


    public function store(Request $request)
    {
        $user = Pengguna::findOrFail(Auth::id());

        DB::beginTransaction();
        try {

            $cartItems = Cart::with(['produk', 'size'])
                ->where('user_id', $user->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang Anda kosong.');
            }

            // 🔥 VALIDASI STOK TERAKHIR
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->size->stok) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Stok produk "' . 
                            $item->produk->nama_produk . 
                            ' (' . $item->size->size . ')" tidak mencukupi.');
                }
            }

            // ===============================
            // HITUNG SUBTOTAL
            // ===============================
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->size->harga * $item->quantity;
            }

            // VALIDASI INPUT
            $request->validate([
                'metode_pembayaran' => 'required|in:transfer,cod',
                'promo_id' => 'nullable|exists:promos,id',
                'poin' => 'nullable|integer|min:0',
            ]);

            $promo = $request->promo_id ? Promo::find($request->promo_id) : null;
            $diskonPromo = 0;

            if ($promo && now()->between($promo->mulai, $promo->akhir)) {
                $diskonPromo = $subtotal * ($promo->diskon / 100);
            }

            $poinDigunakan = min($request->poin ?? 0, $user->jumlah_poin);
            $diskonPoin = $poinDigunakan * 100;

            $totalBayar = max(0, $subtotal - $diskonPromo - $diskonPoin);

            // ===============================
            // BUAT PESANAN
            // ===============================
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'total' => $totalBayar,
                'status' => 'menunggu konfirmasi',
                'metode_pembayaran' => $request->metode_pembayaran,
                'promo_id' => $promo?->id,
                'diskon_dari_promo' => $diskonPromo,
                'poin_digunakan' => $poinDigunakan,
                'diskon_dari_poin' => $diskonPoin,
            ]);

            // ===============================
            // DETAIL PESANAN + KURANGI STOK
            // ===============================
            foreach ($cartItems as $item) {

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'product_size_id' => $item->product_size_id,
                    'quantity' => $item->quantity,
                    'price' => $item->size->harga,
                ]);

                // Kurangi stok size
                $item->size->decrement('stok', $item->quantity);
            }

            // Kurangi poin user
            if ($poinDigunakan > 0) {
                $user->decrement('jumlah_poin', $poinDigunakan);
            }

            // Hapus cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('detail.produk')->findOrFail($id);

        return view('user.pesanan.show', compact('pesanan'));
    }

    public function history()
    {
        $user = Auth::user();
        $pesanan = Pesanan::with('refund')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        return view('user.pesanan.history', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Hanya izinkan perubahan status dari 'dikirim' menjadi 'diterima'
        if ($pesanan->status === 'dikirim' && $request->status === 'diterima') {
            $pesanan->status = 'diterima';
            $pesanan->save();

            AdminNotification::create([
                'tipe' => 'pesanan_diterima_user',
                'pesan' => 'User ' . auth()->user()->username . ' telah menerima pesanan #' . $pesanan->id,
                'url' => route('admin.pesanan.show', $pesanan->id),
            ]);

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi diterima.');
        }

        return redirect()->back()->with('error', 'Status pesanan tidak dapat diubah.');
    }

}
