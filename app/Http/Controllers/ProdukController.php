<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        $products = Produk::with('kategori')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
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
            'jenis'       => 'required|string|max:255', 
            'deskripsi' => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $product)
    {
        $categories = Kategori::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Produk $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis'       => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'stok'       => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Jika ada gambar baru, hapus yang lama
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function showToUser(Request $request)
    {
        $query = Produk::with('kategori')->latest();

        // Pencarian berdasarkan nama
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Kategori::all();

        return view('user.products.index', compact('products', 'categories'));
    }
    
    public function showToUserDetail($id)
    {
        $product = Produk::with('kategori')->findOrFail($id);
        return view('user.products.show', compact('product'));
    }

    public function showReviews($id)
    {
        $product = Produk::with(['reviews.user'])->findOrFail($id);

        return view('admin.products.review', compact('product'));
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $produkId = $review->produk_id;

        $review->delete();

        return redirect()->route('products.reviews', $produkId)
            ->with('success', 'Ulasan berhasil dihapus.');
    }

    public function dashboard()
    {
        $totalProduk = \App\Models\Produk::count();

        $totalPesanan = \App\Models\Pesanan::whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'selesai')
            ->count();

        $totalPenjualanBulanIni = \App\Models\Pesanan::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'selesai')
            ->sum('total');

        $salesData = \App\Models\Pesanan::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total_penjualan')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'selesai')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_penjualan', 'month')
            ->toArray();

        $months = range(1, 12);
        $salesDataFull = [];
        foreach ($months as $month) {
            $salesDataFull[] = $salesData[$month] ?? 0;
        }

        $pesananTerbaru = \App\Models\Pesanan::where('status', '!=', 'selesai')
            ->with(['pengguna', 'detail.produk'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $produkTerlaris = DetailPesanan::select('produk_id', DB::raw('SUM(quantity) as total_terjual'))
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->with('produk') // relasi ke model Produk
            ->first();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalPesanan',
            'totalPenjualanBulanIni',
            'salesDataFull',
            'pesananTerbaru',
            'produkTerlaris'
        ));
    }
}
