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
        $cartItems = Cart::where('user_id', $user->id)->with('produk')->get();
        $alamat = $user->alamat ?? '-';

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->produk->harga * $item->quantity;
        }

        $promos = Promo::where('mulai', '<=', now())
                        ->where('akhir', '>=', now())
                        ->get();

        return view('user.pesanan.checkout', compact('cartItems', 'alamat', 'subtotal', 'promos'));
    }


    public function store(Request $request)
    {
        $user = \App\Models\Pengguna::find(Auth::id());

        DB::beginTransaction();
        try {
            $cartItems = Cart::where('user_id', $user->id)->with('produk')->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Keranjang Anda kosong.');
            }

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->produk->harga * $item->quantity;
            }

            // Validasi input
            $request->validate([
                'metode_pembayaran' => 'required|in:transfer,cod',
                'promo_id' => 'nullable|exists:promos,id',
                'poin' => 'nullable|integer|min:0',
            ]);

            $metode = $request->metode_pembayaran;
            $promoId = $request->promo_id;
            $poinDigunakan = $request->poin ?? 0;

            // Cek poin user cukup
            if ($poinDigunakan > $user->jumlah_poin) {
                return back()->with('error', 'Poin yang Anda gunakan melebihi jumlah poin Anda.');
            }

            $diskonPromo = 0;
            $promo = null;

            if ($promoId) {
                $promo = Promo::find($promoId);

                if ($promo && now()->between($promo->mulai, $promo->akhir)) {
                    $diskonPromo = $subtotal * ($promo->diskon / 100);
                }
            }

            // Diskon dari poin (misal 1 poin = Rp 100)
            $diskonPoin = $poinDigunakan * 100;

            // Total akhir
            $totalBayar = $subtotal - $diskonPromo - $diskonPoin;
            if ($totalBayar < 0) {
                $totalBayar = 0;
            }

            // Buat pesanan
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'total' => $totalBayar,
                'status' => 'menunggu konfirmasi',  // Langsung status diproses setelah bayar
                'metode_pembayaran' => $metode,
                'poin_digunakan' => $poinDigunakan,
                'diskon_dari_poin' => $diskonPoin,
                'promo_id' => $promoId,
                'diskon_dari_promo' => $diskonPromo,
            ]);

            // Simpan detail pesanan
            foreach ($cartItems as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
                    'quantity' => $item->quantity,
                    'price' => $item->produk->harga,
                ]);

                // Kurangi stok produk
                $produk = $item->produk;
                $produk->stok -= $item->quantity;
                if ($produk->stok < 0) {
                    $produk->stok = 0; // optional: agar stok tidak minus
                }
                $produk->save();
            }

            // Notifikasi pesanan baru
            AdminNotification::create([
                'tipe' => 'pesanan_baru',
                'pesan' => 'Pesanan baru oleh ' . auth()->user()->username . ' (#' . $pesanan->id . ')',
                'url' => route('admin.pesanan.show', $pesanan->id),
            ]);

            // Notifikasi stok hampir habis
            foreach ($pesanan->detail as $detail) {
                $produk = $detail->produk;
                if ($produk && $produk->stok <= 5) {
                    AdminNotification::create([
                        'tipe' => 'stok_hampir_habis',
                        'pesan' => 'Stok produk "' . $produk->nama . '" Sisa: ' . $produk->stok,
                        'url' => route('products.edit', $produk->id),
                    ]);
                }
            }

            // Kurangi poin user jika digunakan
            if ($poinDigunakan > 0) {
                $user->jumlah_poin -= $poinDigunakan;
                if ($user->jumlah_poin < 0) {
                    $user->jumlah_poin = 0;
                }
                $user->save();
            }

            // Hapus keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat dan diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
