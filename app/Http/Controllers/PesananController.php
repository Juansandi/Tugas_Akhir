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
        ->with(['produk', 'size', 'paket.detailPakets.size'])
        ->get();

        $alamat = $user->alamat ?? '-';

        $subtotal = 0;

        foreach ($cartItems as $item) {

            if ($item->type === 'paket') {
                $subtotal += $item->paket->harga_paket * $item->quantity;
            } else {
                $subtotal += $item->size->harga * $item->quantity;
            }
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

            // ===============================
            // AMBIL CART + RELASI
            // ===============================
            $cartItems = Cart::with([
                'produk',
                'size',
                'paket.detailPakets.size'
            ])->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang Anda kosong.');
            }

            // ===============================
            // VALIDASI STOK TERAKHIR (FAIL FAST)
            // ===============================
            foreach ($cartItems as $item) {

                // ===== PRODUK =====
                if ($item->type === 'produk') {

                    if (!$item->size) {
                        return back()->with('error', 'Ukuran produk tidak tersedia.');
                    }

                    if ($item->size->stok < $item->quantity) {
                        return back()->with(
                            'error',
                            'Stok produk tidak mencukupi.'
                        );
                    }
                }

                // ===== PAKET =====
                else {

                    if (!$item->paket) {
                        return back()->with('error', 'Paket tidak valid.');
                    }

                    foreach ($item->paket->detailPakets as $detail) {

                        if (!$detail->size) {
                            return back()->with(
                                'error',
                                'Ukuran produk dalam paket tidak tersedia.'
                            );
                        }

                        $need = $detail->quantity * $item->quantity;

                        if ($detail->size->stok < $need) {
                            return back()->with(
                                'error',
                                'Stok paket tidak mencukupi.'
                            );
                        }
                    }
                }
            }

            // ===============================
            // HITUNG SUBTOTAL
            // ===============================
            $subtotal = 0;
            foreach ($cartItems as $item) {
                if ($item->type === 'paket') {
                    $subtotal += $item->paket->harga_paket * $item->quantity;
                } else {
                    $subtotal += $item->size->harga * $item->quantity;
                }
            }

            // ===============================
            // VALIDASI INPUT
            // ===============================
            $request->validate([
                'metode_pembayaran' => 'required|in:transfer,cod',
                'promo_id' => 'nullable|exists:promos,id',
                'poin' => 'nullable|integer|min:0',
            ]);

            // ===============================
            // DISKON PROMO & POIN
            // ===============================
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
            // DETAIL PESANAN + POTONG STOK
            // ===============================
            foreach ($cartItems as $item) {

                // ===== PRODUK =====
                if ($item->type === 'produk') {

                    DetailPesanan::create([
                        'pesanan_id'      => $pesanan->id,
                        'produk_id'       => $item->produk_id,
                        'product_size_id' => $item->product_size_id,
                        'quantity'        => $item->quantity,
                        'price'           => $item->size->harga,
                        'type'            => 'produk',
                    ]);

                    // potong stok size (AMAN)
                    $item->size->update([
                        'stok' => max(0, $item->size->stok - $item->quantity)
                    ]);
                }

                // ===== PAKET =====
                else {

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'paket_id'   => $item->paket_id,
                        'quantity'   => $item->quantity,
                        'price'      => $item->paket->harga_paket,
                        'type'       => 'paket',
                    ]);

                    // potong stok isi paket
                    foreach ($item->paket->detailPakets as $detail) {
                        $detail->size->update([
                            'stok' => max(
                                0,
                                $detail->size->stok
                                - ($detail->quantity * $item->quantity)
                            )
                        ]);
                    }
                }
            }

            // ===============================
            // KURANGI POIN USER
            // ===============================
            if ($poinDigunakan > 0) {
                $user->decrement('jumlah_poin', $poinDigunakan);
            }

            // ===============================
            // HAPUS CART
            // ===============================
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {

            DB::rollBack();

            // SAAT DEVELOPMENT:
            return back()->with('error', $e->getMessage());

            // SAAT PRODUCTION:
            // return back()->with('error', 'Terjadi kesalahan sistem.');
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

        $pesanans = Pesanan::with([
            'refund',
            'chatAdminUnreadForUser',
            'chatKurirUnreadForUser'
        ])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

        return view('user.pesanan.history', compact('pesanans'));
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
