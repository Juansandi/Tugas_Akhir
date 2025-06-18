<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function create($pesanan_id) {
        $pesanan = Pesanan::findOrFail($pesanan_id);
        return view('user.refund.create', compact('pesanan'));
    }

    public function store(Request $request, $pesanan_id) {
        $request->validate([
            'alasan' => 'required',
            'metode_refund' => 'required',
            'nomor_tujuan' => 'required',
            'bukti_foto' => 'nullable|image|max:2048'
        ]);

        $data = $request->only('alasan', 'metode_refund', 'nomor_tujuan');
        $data['pesanan_id'] = $pesanan_id;
        $data['user_id'] = Auth::id();

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti_refund', 'public');
        }

        Refund::create($data);

        return redirect()->route('pesanan.history')->with('success', 'Permintaan refund berhasil diajukan.');
    }

    public function show($id)
    {
        $refund = Refund::with(['pesanan', 'pengguna'])->findOrFail($id);
        return view('user.refund.show', compact('refund'));
    }


    // Untuk admin
    public function adminIndex() {
        $refunds = Refund::with('pengguna', 'pesanan')->latest()->get();
        return view('admin.refund.index', compact('refunds'));
    }

    public function adminShow($id) {
        $refund = Refund::with('pengguna', 'pesanan')->findOrFail($id);
        return view('admin.refund.show', compact('refund'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $refund = Refund::findOrFail($id);

        $request->validate([
            'respon_admin' => 'required|string',
        ]);

        $refund->respon_admin = $request->respon_admin;

        if ($request->action === 'approve') {
            $refund->status = 'disetujui';
        } elseif ($request->action === 'reject') {
            $refund->status = 'ditolak';
        }

        $refund->save();

        return redirect()->route('refund.index')->with('success', 'Status refund berhasil diperbarui.');
    }
}
