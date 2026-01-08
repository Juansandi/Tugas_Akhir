<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        // 1. BUAT QUERY BUILDER
        $query = Produk::with('sizes')
            ->orderBy('nama_produk');

        // 2. SEARCH
        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%' . $request->q . '%');
        }

        // 3. PAGINATION
        $produks = $query->paginate(10)->withQueryString();

        return view('admin.stok.index', compact('produks'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'stok' => 'required|array',
            'stok.*' => 'nullable|integer|min:0'
        ]);

        // 🔑 FILTER: hanya stok yang diisi & > 0
    $stokDiisi = collect($request->stok)
        ->filter(fn ($qty) => $qty !== null && $qty > 0);

    // ❌ JIKA TIDAK ADA YANG DIISI
    if ($stokDiisi->isEmpty()) {
        return back()->with('error', 'Tidak ada stok yang diubah.');
    }


        DB::beginTransaction();
        try {

            foreach ($request->stok as $sizeId => $qty) {
                if ($qty > 0) {
                    ProductSize::where('id', $sizeId)
                        ->increment('stok', $qty);
                }
            }

            DB::commit();
            return back()->with('success', 'Stok berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui stok.');
        }
    }
}
