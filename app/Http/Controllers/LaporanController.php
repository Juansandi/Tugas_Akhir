<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        // TOTAL PENJUALAN (FILTERED)
        $totalPenjualan = DB::table('pesanans')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->sum('total');

        // JUMLAH TRANSAKSI
        $jumlahTransaksi = DB::table('pesanans')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->count();

        // GRAFIK KATEGORI
        $penjualanKategori = DB::table('detail_pesanans')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->where('pesanans.status', 'selesai')
            ->select(
                'kategoris.nama_kategori',
                DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_penjualan')
            )
            ->groupBy('kategoris.nama_kategori')
            ->get();

        return view('admin.laporan.index', compact(
            'totalPenjualan',
            'jumlahTransaksi',
            'penjualanKategori',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $pesanans = DB::table('pesanans')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->get();

        $total = $pesanans->sum('total');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'pesanans', 'total', 'startDate', 'endDate'
        ));

        return $pdf->download('laporan-penjualan.pdf');
    }

    public function detail(Request $request)
    {
        $startDate = $request->start_date
            ? \Carbon\Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        $detailPenjualan = \DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->leftJoin('pakets', 'detail_pesanans.paket_id', '=', 'pakets.id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'pesanans.id as pesanan_id',
                'pesanans.created_at',
                'detail_pesanans.type',
                'detail_pesanans.quantity',
                'detail_pesanans.price',
                \DB::raw("
                    CASE 
                        WHEN detail_pesanans.type = 'produk' THEN produks.nama_produk
                        ELSE pakets.nama_paket
                    END as nama_item
                "),
                \DB::raw('(detail_pesanans.quantity * detail_pesanans.price) as subtotal')
            )
            ->orderBy('pesanans.created_at', 'desc')
            ->get();

        return view('admin.laporan.detail', compact(
            'detailPenjualan',
            'startDate',
            'endDate'
        ));
    }

    public function detailPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        $detailPenjualan = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->leftJoin('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->leftJoin('pakets', 'detail_pesanans.paket_id', '=', 'pakets.id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'pesanans.id as pesanan_id',
                'pesanans.created_at',
                'detail_pesanans.type',
                'detail_pesanans.quantity',
                'detail_pesanans.price',
                DB::raw("
                    CASE 
                        WHEN detail_pesanans.type = 'produk' THEN produks.nama_produk
                        ELSE pakets.nama_paket
                    END as nama_item
                "),
                DB::raw('(detail_pesanans.quantity * detail_pesanans.price) as subtotal')
            )
            ->orderBy('pesanans.created_at', 'desc')
            ->get();

        $total = $detailPenjualan->sum('subtotal');

        $pdf = Pdf::loadView('admin.laporan.detail_pdf', compact(
            'detailPenjualan',
            'total',
            'startDate',
            'endDate'
        ));

        return $pdf->download('laporan-detail-penjualan.pdf');
    }

    public function produkTerlaris(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $produkTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('product_sizes', 'detail_pesanans.product_size_id', '=', 'product_sizes.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'produks.nama_produk as nama_produk',
                'product_sizes.size as ukuran',      // ✅ UKURAN
                'kategoris.nama_kategori as nama_kategori',
                DB::raw('SUM(detail_pesanans.quantity) as total_qty'),
                DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_omzet')
            )
            ->groupBy(
                'produks.nama_produk',
                'product_sizes.size',                // ✅ GROUP PER UKURAN
                'kategoris.nama_kategori'
            )
            ->orderByDesc('total_qty')
            ->get();

        $grafikProdukTerlaris = DB::table('detail_pesanans')
    ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
    ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
    ->join('product_sizes', 'detail_pesanans.product_size_id', '=', 'product_sizes.id')
    ->where('pesanans.status', 'selesai')
    ->whereBetween('pesanans.created_at', [$startDate, $endDate])
    ->select(
        DB::raw("CONCAT(produks.nama_produk, ' (', product_sizes.size, ')') as label"),
        DB::raw('SUM(detail_pesanans.quantity) as total_qty')
    )
    ->groupBy('label')
    ->orderByDesc('total_qty')
    ->limit(5)
    ->get();


        return view('admin.laporan.produk_terlaris', compact(
            'produkTerlaris',
            'grafikProdukTerlaris',
            'startDate',
            'endDate'
        ));
    }

    public function produkTerlarisPdf(Request $request)
    {
        // query SAMA
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $produkTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('product_sizes', 'detail_pesanans.product_size_id', '=', 'product_sizes.id')
            ->join('kategoris', 'produks.kategori_id', '=', 'kategoris.id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'produks.nama_produk as nama_produk',
                'product_sizes.size as ukuran',      // ✅ UKURAN
                'kategoris.nama_kategori as nama_kategori',
                DB::raw('SUM(detail_pesanans.quantity) as total_qty'),
                DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_omzet')
            )
            ->groupBy(
                'produks.nama_produk',
                'product_sizes.size',                // ✅ GROUP PER UKURAN
                'kategoris.nama_kategori'
            )
            ->orderByDesc('total_qty')
            ->get();

        $grafikProdukTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('produks', 'detail_pesanans.produk_id', '=', 'produks.id')
            ->join('product_sizes', 'detail_pesanans.product_size_id', '=', 'product_sizes.id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                DB::raw("CONCAT(produks.nama_produk, ' (', product_sizes.size, ')') as label"),
                DB::raw('SUM(detail_pesanans.quantity) as total_qty')
            )
            ->groupBy('label')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.produk_terlaris_pdf',
            compact('produkTerlaris', 'grafikProdukTerlaris', 'startDate', 'endDate')
        );

        return $pdf->download('laporan.produk_terlaris.pdf');
    }

    public function paketTerlaris(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $paketTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('pakets', 'detail_pesanans.paket_id', '=', 'pakets.id')
            ->whereNotNull('detail_pesanans.paket_id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'pakets.nama_paket as nama_paket',
                DB::raw('SUM(detail_pesanans.quantity) as total_qty'),
                DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_omzet')
            )
            ->groupBy('pakets.nama_paket')
            ->orderByDesc('total_qty')
            ->get();

        return view('admin.laporan.paket_terlaris', compact(
            'paketTerlaris',
            'startDate',
            'endDate'
        ));
    }

    public function paketTerlarisPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : Carbon::now();

        $paketTerlaris = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
            ->join('pakets', 'detail_pesanans.paket_id', '=', 'pakets.id')
            ->whereNotNull('detail_pesanans.paket_id')
            ->where('pesanans.status', 'selesai')
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->select(
                'pakets.nama_paket as nama_paket',
                DB::raw('SUM(detail_pesanans.quantity) as total_qty'),
                DB::raw('SUM(detail_pesanans.quantity * detail_pesanans.price) as total_omzet')
            )
            ->groupBy('pakets.nama_paket')
            ->orderByDesc('total_qty')
            ->get();

        $pdf = Pdf::loadView(
            'admin.laporan.paket_terlaris_pdf',
            compact('paketTerlaris', 'startDate', 'endDate')
        );

        return $pdf->download('laporan-paket-terlaris.pdf');
    }
}
