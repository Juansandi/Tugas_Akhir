<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Pesanan;
use App\Models\AdminNotification;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RefundController extends Controller
{
    const REFUND_WINDOW_HOURS = 24;

    /* =========================
       USER AREA
    ========================= */

    public function create($pesanan_id)
    {
        $pesanan = Pesanan::with('refund')->findOrFail($pesanan_id);

        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pesanan->status !== 'selesai') {
            return back()->with('error', 'Refund hanya dapat diajukan setelah pesanan selesai.');
        }

        if ($pesanan->refund) {
            return back()->with('error', 'Refund untuk pesanan ini sudah diajukan.');
        }

        $jamSejakSelesai = Carbon::parse($pesanan->updated_at)->diffInHours(now());

        if ($jamSejakSelesai > self::REFUND_WINDOW_HOURS) {
            return back()->with(
                'error',
                'Refund hanya dapat diajukan maksimal '
                . self::REFUND_WINDOW_HOURS
                . ' jam setelah pesanan selesai.'
            );
        }

        return view('user.refund.create', compact('pesanan'));
    }

    public function store(Request $request, $pesanan_id)
    {
        $pesanan = Pesanan::with('refund')->findOrFail($pesanan_id);

        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'selesai') {
            abort(403);
        }

        if ($pesanan->refund) {
            return back()->with('error', 'Refund untuk pesanan ini sudah diajukan.');
        }

        $jamSejakSelesai = Carbon::parse($pesanan->updated_at)->diffInHours(now());

        if ($jamSejakSelesai > self::REFUND_WINDOW_HOURS) {
            return back()->with('error', 'Batas waktu pengajuan refund telah berakhir.');
        }

        $request->validate([
            'alasan'         => 'required|string',
            'alasan_lainnya' => 'nullable|string',
            'metode_refund'  => 'required|string',
            'nomor_tujuan'   => 'required|string',
            'bukti_foto'     => 'nullable|image|max:2048',
        ]);

        $alasan = $request->alasan === 'Lainnya'
            ? $request->alasan_lainnya
            : $request->alasan;

        $refund = Refund::create([
            'pesanan_id'    => $pesanan->id,
            'user_id'       => Auth::id(),
            'alasan'        => $alasan,
            'metode_refund' => $request->metode_refund,
            'nomor_tujuan'  => $request->nomor_tujuan,
            'status'        => 'diajukan',
            'refund_amount' => 0,
            'bukti_foto'    => $request->hasFile('bukti_foto')
                ? $request->file('bukti_foto')->store('bukti_refund', 'public')
                : null,
        ]);

        AdminNotification::create([
            'tipe'  => 'refund_diajukan',
            'pesan' => 'User ' . Auth::user()->username
                     . ' mengajukan refund untuk pesanan #'
                     . $pesanan->id,
            'url'   => route('admin.refund.show', $refund->id),
        ]);

        return redirect()->route('pesanan.history')
            ->with('success', 'Permintaan refund berhasil diajukan.');
    }

    public function show($id)
    {
        $refund = Refund::with(['pesanan', 'pengguna'])->findOrFail($id);

        if ($refund->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.refund.show', compact('refund'));
    }

    /* =========================
       ADMIN AREA
    ========================= */

    public function adminIndex()
    {
        $refunds = Refund::with(['pengguna', 'pesanan'])->latest()->get();
        return view('admin.refund.index', compact('refunds'));
    }

    public function adminShow($id)
    {
        $refund = Refund::with(['pengguna', 'pesanan'])->findOrFail($id);
        return view('admin.refund.show', compact('refund'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);

        if ($refund->status !== 'diajukan') {
            return back()->with('error', 'Refund ini sudah diproses.');
        }

        $request->validate([
            'keputusan'     => 'required|in:approve,reject',
            'respon_admin'  => 'required|string',
            'refund_amount' => 'required|numeric|min:0',
        ]);

        if ($request->keputusan === 'approve') {

            if ($request->refund_amount <= 0) {
                return back()->withErrors([
                    'refund_amount' => 'Nominal refund harus lebih dari 0 jika disetujui.'
                ])->withInput();
            }

            $refund->status = 'disetujui';
            $refund->refund_amount = $request->refund_amount;
            $refund->approved_at = now();

        } else {

            // DITOLAK
            $refund->status = 'ditolak';
            $refund->refund_amount = 0;
        }

        $refund->respon_admin = $request->respon_admin;
        $refund->save();

        UserNotification::create([
            'user_id' => $refund->user_id,
            'tipe'    => $refund->status === 'disetujui'
                ? 'refund_disetujui'
                : 'refund_ditolak',
            'pesan'   => 'Refund pesanan #'
                . $refund->pesanan_id
                . ' ' . $refund->status . '.',
            'url'     => route('refund.show', $refund->id),
        ]);

        return redirect()->route('admin.refund.index')
            ->with('success', 'Status refund berhasil diperbarui.');
    }
}
