<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSize;
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
                ProductSize::where('id', $sizeId)
                    ->update(['harga' => $hargaBaru]);
            }

            DB::commit();
            return back()->with('success', 'Harga berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui harga.');
        }
    }
}
