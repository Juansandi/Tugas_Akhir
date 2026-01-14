<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Refund;
use App\Models\Pesanan;
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

        // =========================
        // 1️⃣ PENJUALAN KOTOR
        // =========================
        $totalPenjualanKotor = DB::table('pesanans')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->sum('total');

        // =========================
        // 2️⃣ TOTAL REFUND
        // =========================
        $totalRefund = DB::table('refunds')
            ->where('status', 'disetujui')
            ->whereBetween('approved_at', [$startDate, $endDate])
            ->sum('refund_amount');

        // =========================
        // 3️⃣ PENJUALAN BERSIH
        // =========================
        $totalPenjualanBersih = $totalPenjualanKotor - $totalRefund;

        // =========================
        // 4️⃣ JUMLAH TRANSAKSI
        // =========================
        $jumlahTransaksi = DB::table('pesanans')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->count();

        // =========================
        // 5️⃣ GRAFIK PER KATEGORI
        // (PENJUALAN KOTOR)
        // =========================
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
            'totalPenjualanKotor',
            'totalRefund',
            'totalPenjualanBersih',
            'jumlahTransaksi',
            'penjualanKategori',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
       $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $pesanans = Pesanan::with('refund')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'selesai')
            ->get();

        $total = DB::table('pesanans')
            ->leftJoin('refunds', function ($join) {
                $join->on('refunds.pesanan_id', '=', 'pesanans.id')
                    ->where('refunds.status', 'disetujui');
            })
            ->whereBetween('pesanans.created_at', [$startDate, $endDate])
            ->where('pesanans.status', 'selesai')
            ->selectRaw('SUM(pesanans.total - COALESCE(refunds.refund_amount, 0)) as total_bersih')
            ->value('total_bersih');

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

    public function refund(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        $refunds = Refund::with(['pesanan','pengguna'])
            ->where('status', 'disetujui')
            ->whereBetween('approved_at', [$startDate, $endDate])
            ->orderBy('approved_at', 'desc')
            ->get();

        $totalRefund = $refunds->sum('refund_amount');

        return view('admin.laporan.refund', compact(
            'refunds',
            'totalRefund',
            'startDate',
            'endDate'
        ));
    }

    public function refundPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        $refunds = Refund::with(['pesanan','pengguna'])
            ->where('status', 'disetujui')
            ->whereBetween('approved_at', [$startDate, $endDate])
            ->get();

        $totalRefund = $refunds->sum('refund_amount');

        $pdf = Pdf::loadView(
            'admin.laporan.refund_pdf',
            compact('refunds','totalRefund','startDate','endDate')
        );

        return $pdf->download('laporan-refund.pdf');
    }

}
