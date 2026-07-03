<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\TugasKurir;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminNotification;

class KurirController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $pesananAktif = TugasKurir::where('user_id', $userId)
            ->where('status', 'aktif')
            ->count();

        $pesananSelesai = TugasKurir::where('user_id', $userId)
            ->where('status', 'selesai')
            ->count();

        $pesananTerakhir = TugasKurir::with('pesanan')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('kurir.dashboard', compact(
            'pesananAktif',
            'pesananSelesai',
            'pesananTerakhir'
        ));
    }

    public function pesanan()
    {
        $tugas = TugasKurir::with([
            'pesanan.pengguna',
            'pesanan.detail.produk',
            'pesanan.detail.size',
            'pesanan.detail.paket',
            'pesanan.chatKurir.messages'
        ])
        ->where('user_id', Auth::id())
        ->where('status', 'aktif')
        ->get();

        foreach ($tugas as $item) {
            if (!$item->pesanan->chatKurir) {
                Chat::create([
                    'pesanan_id' => $item->pesanan->id,
                    'type'       => 'kurir',
                ]);
            }
        }

        // reload relasi setelah create
        $tugas->load('pesanan.chatKurir.messages');

        return view('kurir.pesanan', compact('tugas'));
    }

    public function detail(TugasKurir $tugas)
    {
        // keamanan: pastikan tugas milik kurir login
        if ($tugas->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$tugas->pesanan->chatKurir) {
            Chat::create([
                'pesanan_id' => $tugas->pesanan->id,
                'type'       => 'kurir',
                'is_active'  => true,
               ]);
        }

        $tugas->load([
            'pesanan.pengguna',
            'pesanan.detail.produk',
            'pesanan.detail.size',
            'pesanan.detail.paket',
            'pesanan.chatKurir.messages'
        ]);

        return view('kurir.pesanan_detail', compact('tugas'));
    }

    public function profil()
    {
        return view('kurir.profil');
    }

    public function tandaiDikirim(Request $request, $id)
    {
        $tugas = TugasKurir::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'bukti_kirim' => 'nullable|image|max:2048',
            'catatan_kurir' => 'nullable|string|max:255',
        ]);

        $path = null;
        if ($request->hasFile('bukti_kirim')) {
            $path = $request->file('bukti_kirim')
                ->store('bukti_kirim', 'public');
        }

        $tugas->update([
            'status' => 'dikirim',
            'waktu_kirim' => now(),
            'bukti_kirim' => $path,
            'catatan_kurir' => $request->catatan_kurir,
        ]);

        return back()->with('success', 'Pesanan ditandai telah dikirim.');
    }

    public function kirim(Request $request, TugasKurir $tugas)
    {
        abort_if($tugas->user_id !== Auth::id(), 403);

        $request->validate([
            'bukti_kirim' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'catatan_kurir' => 'nullable|string|max:255',
        ]);

        $path = $request->file('bukti_kirim')
            ->store('bukti_kirim', 'public');

        // update tugas kurir
        $tugas->update([
            'status' => 'selesai',
            'waktu_kirim' => now(),
            'bukti_kirim' => $path,
            'catatan_kurir' => $request->catatan_kurir,
        ]);

        // update pesanan → DIKIRIM (BUKAN SELESAI)
        $tugas->pesanan->update([
            'status' => 'dikirim'
        ]);

        // ===============================
        // 🔔 NOTIFIKASI KE ADMIN
        // ===============================
        AdminNotification::create([
            'tipe'  => 'kurir_selesai_kirim',
            'pesan' => 'Kurir ' . auth()->user()->username .
                    ' telah menyelesaikan pengiriman pesanan #' .
                    $tugas->pesanan->id,
            'url'   => route('admin.pesanan.show', $tugas->pesanan->id),
        ]);


        return back()->with('success', 'Pesanan berhasil dikirim.');
    }

    public function riwayat()
    {
        $tugas = TugasKurir::with('pesanan')
            ->where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->latest('updated_at')
            ->paginate(10);

        return view('kurir.riwayat', compact('tugas'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        // pastikan hanya kurir
        if ($user->role !== 'kurir') {
            abort(403);
        }

        $request->validate([
            'username' => 'required|string|max:50',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $user->update([
            'username' => $request->username,
            'no_telp' => $request->no_telp,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        if ($user->role !== 'kurir') {
            abort(403);
        }

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

}
