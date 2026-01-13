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
use App\Models\AlamatPengguna;
use App\Models\Review;
use App\Models\AdminNotification;
use Carbon\Carbon;

class PesananController extends Controller
{

    public function checkoutForm()
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
        ->with(['produk', 'size', 'paket.detailPakets.size'])
        ->get();

        $alamatList = $user->alamatPengguna()->get();
        $alamatUtama = $user->alamatUtama;

        if ($alamatList->isEmpty()) {
        return redirect()
            ->route('profile.show')
            ->with('error', 'Silakan tambahkan alamat terlebih dahulu sebelum checkout.');
        }

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

        return view('user.pesanan.checkout', compact('cartItems', 'alamatList','alamatUtama' , 'subtotal', 'promos'));
    }

    public function store(Request $request)
    {
        $user = Pengguna::findOrFail(Auth::id());

        DB::beginTransaction();
        try {

            // ===============================
            // VALIDASI INPUT UTAMA
            // ===============================
            $request->validate([
                'metode_pembayaran' => 'required|in:transfer,cod',
                'promo_id' => 'nullable|exists:promos,id',
                'poin' => 'nullable|integer|min:0',
                'alamat_id' => 'required|exists:alamat_pengguna,id', // ⬅️ WAJIB
            ]);

            // ===============================
            // AMBIL & VALIDASI ALAMAT USER
            // ===============================
            $alamat = AlamatPengguna::where('id', $request->alamat_id)
                ->where('pengguna_id', $user->id)
                ->firstOrFail();

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
            // VALIDASI STOK (FAIL FAST)
            // ===============================
            foreach ($cartItems as $item) {

                if ($item->type === 'produk') {

                    if (!$item->size || $item->size->stok < $item->quantity) {
                        return back()->with('error', 'Stok produk tidak mencukupi.');
                    }

                } else {

                    if (!$item->paket) {
                        return back()->with('error', 'Paket tidak valid.');
                    }

                    foreach ($item->paket->detailPakets as $detail) {
                        $need = $detail->quantity * $item->quantity;
                        if (!$detail->size || $detail->size->stok < $need) {
                            return back()->with('error', 'Stok paket tidak mencukupi.');
                        }
                    }
                }
            }

            // ===============================
            // HITUNG SUBTOTAL
            // ===============================
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->type === 'paket'
                    ? $item->paket->harga_paket * $item->quantity
                    : $item->size->harga * $item->quantity;
            }

            // ===============================
            // DISKON PROMO & POIN
            // ===============================
            $promo = $request->promo_id ? Promo::find($request->promo_id) : null;
            $diskonPromo = ($promo && now()->between($promo->mulai, $promo->akhir))
                ? $subtotal * ($promo->diskon / 100)
                : 0;

            $poinDigunakan = min($request->poin ?? 0, $user->jumlah_poin);
            $diskonPoin = $poinDigunakan * 100;

            $totalBayar = max(0, $subtotal - $diskonPromo - $diskonPoin);

            // ===============================
            // BUAT PESANAN (SNAPSHOT ALAMAT)
            // ===============================
            $pesanan = Pesanan::create([
                'user_id' => $user->id,
                'total' => $totalBayar,
                'status' => $request->metode_pembayaran === 'transfer'
                    ? 'belum_dibayar'
                    : 'diproses',
                'metode_pembayaran' => $request->metode_pembayaran,
                'promo_id' => $promo?->id,
                'diskon_dari_promo' => $diskonPromo,
                'poin_digunakan' => $poinDigunakan,
                'diskon_dari_poin' => $diskonPoin,
                'alamat_pengiriman' => $alamat->alamat,
                'no_telp_pengiriman' => $alamat->no_telp,
            ]);

            // ===============================
            // DETAIL PESANAN + POTONG STOK
            // ===============================
            foreach ($cartItems as $item) {

                if ($item->type === 'produk') {

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'produk_id' => $item->produk_id,
                        'product_size_id' => $item->product_size_id,
                        'quantity' => $item->quantity,
                        'price' => $item->size->harga,
                        'type' => 'produk',
                    ]);

                    $item->size->decrement('stok', $item->quantity);

                } else {

                    DetailPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'paket_id' => $item->paket_id,
                        'quantity' => $item->quantity,
                        'price' => $item->paket->harga_paket,
                        'type' => 'paket',
                    ]);

                    foreach ($item->paket->detailPakets as $detail) {
                        $detail->size->decrement(
                            'stok',
                            $detail->quantity * $item->quantity
                        );
                    }
                }
            }

            // ===============================
            // KURANGI POIN & HAPUS CART
            // ===============================
            if ($poinDigunakan > 0) {
                $user->decrement('jumlah_poin', $poinDigunakan);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()
                ->route('pesanan.show', $pesanan->id)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function formPembayaran(Pesanan $pesanan)
    {
        abort_if(
            $pesanan->user_id !== auth()->id() ||
            $pesanan->status !== 'belum_dibayar',
            403
        );

        return view('user.pesanan.pembayaran', compact('pesanan'));
    }

    public function uploadBukti(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pesanan->status !== 'belum_dibayar') {
            return redirect()
                ->route('pesanan.show', $pesanan->id)
                ->with('info', 'Pembayaran sudah dikirim.');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('bukti_bayar')
            ->store('bukti_bayar', 'public');

        $pesanan->update([
            'bukti_bayar' => $path,
            'waktu_bayar' => now(),
            'status' => 'menunggu_konfirmasi'
        ]);

        return redirect()
            ->route('pesanan.show', $pesanan->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim.');
    }

    public function show($id)
    {
        $pesanan = Pesanan::with('detail.produk')->findOrFail($id);

        $reviewedProdukIds = Review::where('user_id', auth()->id())
        ->where('pesanan_id', $pesanan->id)
        ->pluck('produk_id')
        ->toArray();

        return view('user.pesanan.show', compact('pesanan', 'reviewedProdukIds'));
    }

    public function history()
    {
        $user = Auth::user();

        Pesanan::autoCancelExpired(); 

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
