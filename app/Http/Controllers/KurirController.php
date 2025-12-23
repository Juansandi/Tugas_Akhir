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
        return view('kurir.dashboard');
    }

    public function pesanan()
    {
        $tugas = TugasKurir::with('pesanan')
            ->where('user_id', Auth::id())
            ->where('status', 'aktif')
            ->get();

        return view('kurir.pesanan', compact('tugas'));
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
