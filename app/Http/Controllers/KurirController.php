<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\TugasKurir;
use Illuminate\Support\Facades\Auth;

class KurirController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $pesananAktif = TugasKurir::where('user_id', $userId)
            ->where('status', 'aktif')
            ->count();

        $pesananSelesai = TugasKurir::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        $pesananTerakhir = TugasKurir::with('pesanan')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('kurir.dashboard', compact(
            'pesananAktif',
            'pesananSelesai',
            'pesananTerakhir'
        ));
    }

    public function pesanan()
    {
        $tugas = TugasKurir::with([
            'pesanan.pengguna',
            'pesanan.detail.produk',
            'pesanan.detail.size',
            'pesanan.detail.paket',
            'pesanan.chatKurir.messages'
        ])
        ->where('user_id', Auth::id())
        ->where('status', 'aktif')
        ->get();

        return view('kurir.pesanan', compact('tugas'));
    }

    public function detail(TugasKurir $tugas)
    {
        // keamanan: pastikan tugas milik kurir login
        if ($tugas->user_id !== Auth::id()) {
            abort(403);
        }

        $tugas->load([
            'pesanan.pengguna',
            'pesanan.detail.produk',
            'pesanan.detail.size',
            'pesanan.detail.paket',
            'pesanan.chatKurir.messages'
        ]);

        return view('kurir.pesanan_detail', compact('tugas'));
    }

    public function profil()
    {
        return view('kurir.profil');
    }

    public function selesai($id)
    {
        $tugas = TugasKurir::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $tugas->update(['status' => 'selesai']);
        $tugas->pesanan->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan selesai dikirim');
    }

    public function riwayat()
    {
        $tugas = TugasKurir::with('pesanan')
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kurir.riwayat', compact('tugas'));
    }

}
