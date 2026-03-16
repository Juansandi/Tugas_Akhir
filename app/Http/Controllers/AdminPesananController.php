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
        ])
        ->withSum(
            ['refund as refund_total' => function ($q) {
                $q->where('status', 'disetujui');
            }],
            'refund_amount'
        )
        ->orderBy('created_at', 'desc');

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
            'chatAdmin',
            'deliverySlot',
        ])->findOrFail($id);

        if (!$pesanan->chatAdmin) {
            Chat::create([
                'pesanan_id' => $pesanan->id,
                'type'       => 'admin',
            ]);

            $pesanan->load('chatAdmin');
        }

        $kurirs = Pengguna::where('role', 'kurir')
            ->where('is_active', true)
            ->get();

        return view('admin.pesanan.show', compact('pesanan', 'kurirs'));
    }

    public function verifikasiPembayaran(Request $request, Pesanan $pesanan)
    {
        abort_if($pesanan->status !== 'menunggu_konfirmasi', 403);

        if ($request->aksi === 'terima') {
            $pesanan->update(['status' => 'diproses']);

            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe'    => 'pesanan_diproses',
                'pesan'   => 'Pesanan #' . $pesanan->id . ' sedang diproses.',
                'url'     => route('pesanan.show', $pesanan->id),
            ]);
        } else {
            $pesanan->update(['status' => 'belum_dibayar']);

            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe'    => 'pembayaran_ditolak',
                'pesan'   => 'Pembayaran pesanan #' . $pesanan->id . ' ditolak.',
                'url'     => route('pesanan.show', $pesanan->id),
            ]);
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

            $kurir = Pengguna::where('id', $request->kurir_id)
                ->where('role', 'kurir')
                ->where('is_active', true)
                ->first();

            if (!$kurir) {
                return back()->with('error', 'Kurir tidak valid atau sudah dinonaktifkan.');
            }

            TugasKurir::firstOrCreate(
                ['pesanan_id' => $pesanan->id],
                [
                    'user_id' => $kurir->id,
                    'status'  => 'aktif'
                ]
            );
        }

        if ($target === 'selesai') {

            $poin = intval($pesanan->total / 1000);
            $pesanan->poin_diperoleh = $poin;

            if ($pesanan->pengguna) {
                $pesanan->pengguna->increment('jumlah_poin', $poin);
            }

            // simpan waktu pesanan selesai
            $pesanan->selesai_at = now();
        }

        $pesanan->update(['status' => $target]);

        if ($target === 'dikirim') {
            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe'    => 'pesanan_dikirim',
                'pesan'   => 'Pesanan #' . $pesanan->id . ' telah dikirim.',
                'url'     => route('pesanan.show', $pesanan->id),
            ]);
        }

        if ($target === 'selesai') {
            UserNotification::create([
                'user_id' => $pesanan->user_id,
                'tipe'    => 'pesanan_selesai',
                'pesan'   => 'Pesanan #' . $pesanan->id . ' telah selesai.',
                'url'     => route('pesanan.show', $pesanan->id),
            ]);
        }

        return back()->with('success', 'Status pesanan diperbarui.');
    }
}
