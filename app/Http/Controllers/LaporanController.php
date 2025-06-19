<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Hari ini
        $today = Carbon::today();
        $harian = DB::table('pesanans')
            ->whereDate('created_at', $today)
            ->where('status', 'selesai')
            ->sum('total');

        // Mingguan
        $mingguan = DB::table('pesanans')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 'selesai')
            ->sum('total');

        // Bulanan
        $bulanan = DB::table('pesanans')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'selesai')
            ->sum('total');

        // Grafik berdasarkan kategori
        $penjualanKategori = DB::table('detail_pesanans')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->select('kategoris.nama_kategori as kategori', DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_penjualan'))
            ->where('pesanans.status', 'selesai')
            ->groupBy('kategoris.nama_kategori')
            ->get();

        return view('admin.laporan.index', compact('harian', 'mingguan', 'bulanan', 'penjualanKategori'));
    }
}
