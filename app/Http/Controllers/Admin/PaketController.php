<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Produk;
use App\Models\ProductSize;
use App\Models\DetailPaket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::latest()->get();
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        $produks = Produk::with('sizes')->get();
        return view('admin.paket.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required',
            'harga_paket' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.product_size_id' => 'nullable|exists:product_sizes,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $items = collect($request->items)->filter(function ($item) {
            return isset($item['quantity']) && $item['quantity'] > 0;
        });

        if ($items->isEmpty()) {
            return back()->with('error', 'Pilih minimal 1 produk untuk paket.');
        }


        DB::beginTransaction();
        try {
            $data = $request->only(['nama_paket', 'deskripsi', 'harga_paket', 'status']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('pakets', 'public');
            }

            $paket = Paket::create($data);

            foreach ($items as $item) {
                DetailPaket::create([
                    'paket_id' => $paket->id,
                    'produk_id' => $item['produk_id'],
                    'product_size_id' => $item['product_size_id'] ?? null,
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Paket $paket)
    {
        $paket->load('detailPakets.produk', 'detailPakets.size');
        $produks = Produk::with('sizes')->get();

        $existingItems = $paket->detailPakets->map(function ($i) {
            return [
                'produk'    => $i->produk->nama_produk,
                'size'      => optional($i->size)->size,
                'produk_id' => $i->produk_id,
                'size_id'   => $i->product_size_id,
                'qty'       => $i->quantity,
            ];
        });

        return view('admin.paket.edit', compact(
            'paket',
            'produks',
            'existingItems'
        ));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'nama_paket' => 'required',
            'harga_paket' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.product_size_id' => 'nullable|exists:product_sizes,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // filter item valid
            $items = collect($request->items)->filter(fn ($i) =>
                isset($i['quantity']) && $i['quantity'] > 0
            );

            if ($items->isEmpty()) {
                return back()->with('error', 'Minimal 1 produk dalam paket.');
            }

            $data = $request->only([
                'nama_paket',
                'deskripsi',
                'harga_paket',
                'status'
            ]);

            // upload gambar baru
            if ($request->hasFile('image')) {
                if ($paket->image) {
                    Storage::disk('public')->delete($paket->image);
                }
                $data['image'] = $request->file('image')->store('pakets', 'public');
            }

            $paket->update($data);

            // hapus detail lama
            DetailPaket::where('paket_id', $paket->id)->delete();

            // simpan detail baru
            foreach ($items as $item) {
                DetailPaket::create([
                    'paket_id' => $paket->id,
                    'produk_id' => $item['produk_id'],
                    'product_size_id' => $item['product_size_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.paket.index')
                ->with('success', 'Paket berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Paket $paket)
    {
        if ($paket->image) {
            Storage::disk('public')->delete($paket->image);
        }

        $paket->delete();
        return back()->with('success', 'Paket dihapus');
    }
}
