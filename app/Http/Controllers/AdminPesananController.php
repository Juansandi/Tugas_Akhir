<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class AdminPesananController extends Controller
{
    public function index()
    {
        $pesananList = Pesanan::with(['pengguna', 'detail.produk'])->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('pesananList'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['pengguna', 'detail.produk'])->findOrFail($id);
        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $status = $request->input('status');
        $pesanan->status = $status;

        // Jika ada input nomor resi
        if ($status === 'dikirim' && $request->filled('no_resi')) {
            $pesanan->no_resi = $request->input('no_resi');
        }

        // Jika status berubah menjadi selesai, hitung poin dan update pengguna
        if ($status === 'selesai') {
            // Hitung poin: 1 poin = 100 rupiah
            $poin = intval($pesanan->total / 1000);
            $pesanan->poin_diperoleh = $poin;

            if ($pesanan->pengguna) {
                $pesanan->pengguna->jumlah_poin += $poin;
                $pesanan->pengguna->save();
            }
        }

        $pesanan->save();

        return redirect()->route('admin.pesanan.show', $id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
