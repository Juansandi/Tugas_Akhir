<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Pesanan;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('admin.pengguna.daftar_pengguna', compact('penggunas'));
    }

    public function riwayat($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pesanan = Pesanan::with('detail.produk')->where('user_id', $id)->orderBy('created_at', 'desc')->get();

        return view('admin.pengguna.riwayat', compact('pengguna', 'pesanan'));
    }

}