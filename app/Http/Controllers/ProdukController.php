<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

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

    public function showToUser()
    {
        $products = Produk::with('kategori')->latest()->paginate(12);
        return view('user.products.index', compact('products'));
    }
    
}
