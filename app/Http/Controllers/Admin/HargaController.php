<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSize;
use App\Models\PriceHistory;
use Illuminate\Support\Facades\DB;

class HargaController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Produk::with('sizes')
            ->orderBy('nama_produk');

        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', '%' . $request->q . '%');
        }

        $produks = $query->paginate(10)->withQueryString();

        return view('admin.harga.index', compact('produks'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'harga' => 'required|array',
            'harga.*' => 'nullable|numeric|min:0'
        ]);

        $hargaDiubah = collect($request->harga)
            ->filter(fn ($v) => $v !== null);

        if ($hargaDiubah->isEmpty()) {
            return back()->with('error', 'Tidak ada harga yang diubah.');
        }

        DB::beginTransaction();
        try {
            foreach ($hargaDiubah as $sizeId => $hargaBaru) {

                $size = ProductSize::findOrFail($sizeId);

                // ✅ SIMPAN HISTORI HARGA
                if ($size->harga != $hargaBaru) {
                    PriceHistory::create([
                        'produk_id'        => $size->produk_id,
                        'product_size_id'  => $size->id,
                        'harga_lama'       => $size->harga,
                        'harga_baru'       => $hargaBaru,
                        'pengguna_id'      => auth()->id()
                    ]);
                }

                // ✅ UPDATE HARGA
                $size->update([
                    'harga' => $hargaBaru
                ]);
            }

            DB::commit();
            return back()->with('success', 'Harga berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui harga.');
        }
    }
}
