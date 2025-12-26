<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;

class PaketUserController extends Controller
{
    public function index()
    {
        $pakets = Paket::with('detailPakets.produk')
            ->where('status', 'aktif')
            ->latest()
            ->get();

        return view('user.paket.index', compact('pakets'));
    }

    public function show(Paket $paket)
    {
        $paket->load('detailPakets.produk', 'detailPakets.size');

        return view('user.paket.show', compact('paket'));
    }
}
