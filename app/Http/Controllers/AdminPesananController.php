<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\UserNotification;
use App\Models\TugasKurir;

class AdminPesananController extends Controller
{
    public function index()
    {
        $pesananList = Pesanan::with(['pengguna', 'detail.produk'])->orderBy('created_at', 'desc')->get();
        return view('admin.pesanan.index', compact('pesananList'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detail.produk', 'detail.size', 'pengguna'])->findOrFail($id);

        $kurirs = Pengguna::where('role', 'kurir')->get();

        return view('admin.pesanan.show', compact('pesanan', 'kurirs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $status = $request->input('status');
        $pesanan->status = $status;

        // Jika ada input nomor resi
        if ($request->status === 'dikirim') {

            $request->validate([
                'kurir_id' => 'required|exists:pengguna,id'
            ]);

            // update status pesanan
            $pesanan->status = 'dikirim';
            $pesanan->save();

            // buat tugas kurir
            TugasKurir::create([
                'pesanan_id' => $pesanan->id,
                'user_id'    => $request->kurir_id,
                'status'     => 'aktif',
            ]);

            return back()->with('success', 'Pesanan dikirim dan kurir berhasil ditugaskan.');
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

            // Notifikasi ke user: pesanan selesai
            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe' => 'pesanan_selesai',
                'pesan' => 'Pesanan #' . $pesanan->id . ' telah selesai. Terima kasih telah berbelanja!',
                'url' => route('pesanan.show', $pesanan->id),
            ]);
        }
        
        // Notifikasi ke user berdasarkan status lainnya
        if ($status === 'diproses') {
            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe' => 'pesanan_diproses',
                'pesan' => 'Pesanan #' . $pesanan->id . ' sedang diproses',
                'url' => route('pesanan.show', $pesanan->id),
            ]);
        } elseif ($status === 'dikirim') {
            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe' => 'pesanan_dikirim',
                'pesan' => 'Pesanan #' . $pesanan->id . ' sedang dikirim ' . $pesanan->no_resi,
                'url' => route('pesanan.show', $pesanan->id),
            ]);
        }

        $pesanan->save();

        return redirect()->route('admin.pesanan.show', $id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
