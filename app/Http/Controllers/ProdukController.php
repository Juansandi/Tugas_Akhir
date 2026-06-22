<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\ProductSize;
use App\Models\PriceHistory;
use App\Models\Refund;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['kategori', 'sizes'])->latest();

        // 🔍 SEARCH PRODUK
        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%' . $request->q . '%');
        }

        // 🏷️ FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Kategori::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('admin.products.create', compact('categories'));
    }

    public function createProduk(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis'       => 'nullable|string|max:255', 
            'deskripsi' => 'nullable|string',
            'harga'       => 'nullable|numeric|min:0',
            'stok'       => 'nullable|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg',

            'sizes'             => 'nullable|array',
            'sizes.*.size'      => 'nullable|string',
            'sizes.*.stok'      => 'nullable|integer|min:0',
            'sizes.*.harga'     => 'nullable|numeric|min:0',
        ]);

         // ambil semua input kecuali sizes
        $data = $request->except('sizes');

        // default value
        $data['harga'] = $data['harga'] ?? 0;
        $data['stok']  = $data['stok']  ?? 0;

        // Simpan gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Produk::create($data);

        if ($request->sizes) {
            foreach ($request->sizes as $size) {
                if (!empty($size['size'])) {
                    ProductSize::create([
                        'produk_id' => $product->id,
                        'size'      => $size['size'],
                        'stok'      => $size['stok'] ?? 0,
                        'harga'     => $size['harga'] ?? $product->harga,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $product)
    {
        $categories = Kategori::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Produk $product)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'jenis'       => 'nullable|string|max:255',
                'deskripsi'   => 'nullable|string',
                'kategori_id' => 'required|exists:kategoris,id',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

                'sizes'             => 'nullable|array',
                'sizes.*.id'        => 'nullable|integer|exists:product_sizes,id',
                'sizes.*.size'      => 'nullable|string',
                'sizes.*.stok'      => 'nullable|integer|min:0',
                'sizes.*.harga'     => 'nullable|numeric|min:0',
            ]);

            // =====================
            // UPDATE DATA PRODUK
            // =====================
            $data = $request->except('sizes');

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);
            // =====================
            // HAPUS UKURAN YANG DIHILANGKAN
            // =====================
            $submittedIds = collect($request->sizes ?? [])
                ->pluck('id')
                ->filter()
                ->toArray();

            $deletedSizes = $product->sizes()
                ->whereNotIn('id', $submittedIds)
                ->get();

            foreach ($deletedSizes as $size) {

                if (
                    $size->detailTransaksiProduks()->exists() ||
                    $size->carts()->exists()
                ) {
                    throw new \Exception(
                        "Ukuran {$size->size} tidak dapat dihapus karena sudah digunakan."
                    );
                }

                $size->delete();
}
            // =====================
            // UPDATE UKURAN + HISTORI HARGA
            // =====================
            if ($request->sizes) {
                foreach ($request->sizes as $sizeData) {

                    // UPDATE ukuran lama
                    if (!empty($sizeData['id'])) {
                        $size = ProductSize::findOrFail($sizeData['id']);

                        // 👉 SIMPAN HISTORI JIKA HARGA BERUBAH
                        if (
                            isset($sizeData['harga']) &&
                            $size->harga != $sizeData['harga']
                        ) {
                            PriceHistory::create([
                                'produk_id'       => $product->id,
                                'product_size_id' => $size->id,
                                'harga_lama'      => $size->harga,
                                'harga_baru'      => $sizeData['harga'],
                                'pengguna_id'     => auth()->id(),
                            ]);

                            // Update harga
                            $size->harga = $sizeData['harga'];
                        }

                        // Update atribut lain
                        $size->size = $sizeData['size'];
                        $size->stok = $sizeData['stok'];
                        $size->save();

                    } else {
                        // TAMBAH ukuran baru (tidak perlu histori)
                        if (!empty($sizeData['size'])) {
                            ProductSize::create([
                                'produk_id' => $product->id,
                                'size'      => $sizeData['size'],
                                'stok'      => $sizeData['stok'] ?? 0,
                                'harga'     => $sizeData['harga'] ?? $product->harga,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui produk.');
        }
    }

    public function destroy(Produk $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function showToUser(Request $request)
    {
        $query = Produk::with(['kategori', 'sizes'])->latest();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // FILTER HARGA → DARI SIZE TERMURAH
        if ($request->filled('min_price')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->where('harga', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->where('harga', '<=', $request->max_price);
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Kategori::all();

        return view('user.products.index', compact('products', 'categories'));
    }

    
    public function showToUserDetail($id)
    {
        $product = Produk::with(['kategori', 'sizes', 'reviews.user'])->findOrFail($id);
        
        $avgRating = round($product->reviews()->avg('rating'), 1);
        $totalReviews = $product->reviews()->count();

        $limitedReviews = $product->reviews()
        ->latest()
        ->take(3)
        ->get();

        return view('user.products.show', compact('product', 'avgRating', 'totalReviews', 'limitedReviews'));
    }

    public function reviews($id)
    {
        $product = Produk::with('reviews.user')->findOrFail($id);

        return view('user.products.reviews', compact('product'));
    }
   
    public function showReviews($id)
    {
        $product = Produk::with('reviews.user')->findOrFail($id);

        return view('admin.products.review', compact('product'));
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $produkId = $review->produk_id;

        $review->delete();

        return redirect()->route('admin.products.reviews', $produkId)
            ->with('success', 'Ulasan berhasil dihapus.');
    }

    public function dashboard()
    {
        $totalProduk = Produk::count();

        $pesananMasukHariIni = Pesanan::whereDate('created_at', today())->count();

        $pesananAktifHariIni = Pesanan::whereDate('created_at', today())
            ->where('status', '!=', 'selesai')
            ->count();

        $totalPenjualanKotor = Pesanan::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', 'selesai')
            ->sum('total');

        $totalRefundBulanIni = Refund::where('status', 'disetujui')
            ->whereYear('approved_at', now()->year)
            ->whereMonth('approved_at', now()->month)
            ->sum('refund_amount');

        $totalPenjualanBulanIni = $totalPenjualanKotor - $totalRefundBulanIni;

        $salesKotor = Pesanan::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('created_at', now()->year)
            ->where('status', 'selesai')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $refunds = Refund::select(
                DB::raw('MONTH(approved_at) as month'),
                DB::raw('SUM(refund_amount) as total')
            )
            ->whereYear('approved_at', now()->year)
            ->where('status', 'disetujui')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $salesDataFull = [];
        for ($i = 1; $i <= 12; $i++) {
            $kotor  = $salesKotor[$i] ?? 0;
            $refund = $refunds[$i] ?? 0;
            $salesDataFull[] = $kotor - $refund;
        }

        $pesananTerbaru = Pesanan::where('status', '!=', 'selesai')
            ->with(['pengguna', 'detail.produk', 'detail.paket'])
            ->latest()
            ->limit(5)
            ->get();

        $produkTerlaris = DetailPesanan::join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->where('pesanans.status', 'selesai')
            ->whereYear('pesanans.created_at', now()->year)
            ->whereMonth('pesanans.created_at', now()->month)
            ->select('produk_id', DB::raw('SUM(quantity) as total_terjual'))
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->first();

        return view('admin.dashboard', compact(
            'totalProduk',
            'pesananMasukHariIni',
            'pesananAktifHariIni',
            'totalPenjualanKotor',
            'totalRefundBulanIni',
            'totalPenjualanBulanIni',
            'salesDataFull',
            'pesananTerbaru',
            'produkTerlaris'
        ));
    }
}
