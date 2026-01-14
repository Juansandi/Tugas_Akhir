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
    // ⏱️ BATAS JAM REFUND (BAHAN POKOK)
    const REFUND_WINDOW_HOURS = 24;

    /**
     * FORM AJUKAN REFUND (USER)
     */
    public function create($pesanan_id)
    {
        $pesanan = Pesanan::with('refund')->findOrFail($pesanan_id);

        // 1️⃣ Pastikan pesanan milik user
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // 2️⃣ Status harus selesai
        if ($pesanan->status !== 'selesai') {
            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('error', 'Refund hanya dapat diajukan setelah pesanan selesai.');
        }

        // 3️⃣ Cegah refund ganda
        if ($pesanan->refund) {
            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('error', 'Refund untuk pesanan ini sudah diajukan.');
        }

        // 4️⃣ Cek refund window (24 JAM)
        $jamSejakSelesai = Carbon::parse($pesanan->updated_at)
            ->diffInHours(now());

        if ($jamSejakSelesai > self::REFUND_WINDOW_HOURS) {
            return redirect()->route('pesanan.show', $pesanan->id)
                ->with(
                    'error',
                    'Refund hanya dapat diajukan maksimal '
                    . self::REFUND_WINDOW_HOURS
                    . ' jam setelah pesanan selesai.'
                );
        }

        return view('user.refund.create', compact('pesanan'));
    }

    /**
     * SIMPAN REFUND (USER)
     */
    public function store(Request $request, $pesanan_id)
    {
        $pesanan = Pesanan::with('refund')->findOrFail($pesanan_id);

        // ⛔ Double protection
        if ($pesanan->user_id !== Auth::id() || $pesanan->status !== 'selesai') {
            abort(403);
        }

        if ($pesanan->refund) {
            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('error', 'Refund untuk pesanan ini sudah diajukan.');
        }

        // ⏱️ Refund window (24 JAM)
        $jamSejakSelesai = Carbon::parse($pesanan->updated_at)
            ->diffInHours(now());

        if ($jamSejakSelesai > self::REFUND_WINDOW_HOURS) {
            return redirect()->route('pesanan.show', $pesanan->id)
                ->with('error', 'Batas waktu pengajuan refund telah berakhir.');
        }

        $request->validate([
            'alasan'         => 'required|string',
            'alasan_lainnya' => 'nullable|string',
            'metode_refund'  => 'required|string',
            'nomor_tujuan'   => 'required|string',
            'bukti_foto'     => 'nullable|image|max:2048',
        ]);

        // 🧠 Handle alasan "Lainnya"
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
            'bukti_foto'    => $request->hasFile('bukti_foto')
                                ? $request->file('bukti_foto')
                                    ->store('bukti_refund', 'public')
                                : null,
        ]);

        // 🔔 Notifikasi admin
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

    /**
     * DETAIL REFUND (USER)
     */
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
        $refunds = Refund::with(['pengguna', 'pesanan'])
            ->latest()
            ->get();

        return view('admin.refund.index', compact('refunds'));
    }

    public function adminShow($id)
    {
        $refund = Refund::with(['pengguna', 'pesanan'])
            ->findOrFail($id);

        return view('admin.refund.show', compact('refund'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);

        if ($refund->status !== 'diajukan') {
            return back()->with('error', 'Refund ini sudah diproses.');
        }

        $request->validate([
            'respon_admin' => 'required|string',
            'action'       => 'required|in:approve,reject',
            'refund_amount'=> 'required_if:action,approve|numeric|min:1',
        ]);

        $refund->respon_admin = $request->respon_admin;
        if ($request->action === 'approve') {
            $refund->status = 'disetujui';
            $refund->refund_amount = $request->refund_amount; // input admin
            $refund->approved_at = now();
        } else {
            $refund->status = 'ditolak';
        }


        $refund->save();

        // 🔔 Notifikasi user
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

        return redirect()->route('refund.index')
            ->with('success', 'Status refund berhasil diperbarui.');
    }
}
