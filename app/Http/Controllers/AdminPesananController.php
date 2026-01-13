<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\UserNotification;
use App\Models\TugasKurir;
use App\Models\Chat;

class AdminPesananController extends Controller
{
    public function index(Request $request)
    {
        // 🔥 AUTO CANCEL (sementara, tanpa scheduler)
        Pesanan::autoCancelExpired();

        $query = Pesanan::with([
            'pengguna',
            'detail.produk',
            'tugasKurir',
            'chatAdmin' => function ($q) {
                $q->withCount([
                    'messages as unread_count' => function ($mq) {
                        $mq->where('sender_type', 'user')
                           ->where('is_read', false);
                    }
                ]);
            }
        ])->orderBy('created_at', 'desc');

        // 🔍 FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesananList = $query->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesananList'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with([
            'detail.produk',
            'detail.size',
            'detail.paket.detailPakets.size',
            'pengguna',
            'tugasKurir',
        ])->findOrFail($id);

        $kurirs = Pengguna::where('role', 'kurir')->get();

        return view('admin.pesanan.show', compact('pesanan', 'kurirs'));
    }

    public function verifikasiPembayaran(Request $request, Pesanan $pesanan)
    {
        abort_if($pesanan->status !== 'menunggu_konfirmasi', 403);

        if ($request->aksi === 'terima') {
            $pesanan->update(['status' => 'diproses']);
        } else {
            $pesanan->update(['status' => 'belum_dibayar']);
        }

        return back()->with('success', 'Status pembayaran diperbarui.');
    }


    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $allowed = [
            'menunggu_konfirmasi' => ['diproses'],
            'diproses'            => ['dikirim'],
            'dikirim'             => ['diterima', 'selesai'],
            'diterima'            => ['selesai'],
        ];

        $current = $pesanan->status;
        $target  = $request->status;

        if (!isset($allowed[$current]) || !in_array($target, $allowed[$current])) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        if ($target === 'dikirim') {
            $request->validate([
                'kurir_id' => 'required|exists:pengguna,id'
            ]);

            TugasKurir::create([
                'pesanan_id' => $pesanan->id,
                'user_id'    => $request->kurir_id,
                'status'     => 'aktif'
            ]);
        }

        if ($target === 'selesai') {
            $poin = intval($pesanan->total / 1000);
            $pesanan->poin_diperoleh = $poin;

            if ($pesanan->pengguna) {
                $pesanan->pengguna->increment('jumlah_poin', $poin);
            }
        }

        $pesanan->update(['status' => $target]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
