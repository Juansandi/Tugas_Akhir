<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\AlamatPengguna;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        $alamatList = $user->alamatPengguna()->orderByDesc('is_default')->get();

        return view('user.profile.alamat', compact('alamatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat' => 'required',
            'label' => 'nullable|string|max:50',
        ]);
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();
        // Jika alamat pertama → jadikan default
        $isFirst = $user->alamatPengguna()->count() === 0;

        AlamatPengguna::create([
            'pengguna_id' => Auth::id(),
            'label' => $request->label,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'is_default' => $isFirst,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan');
    }

    public function setDefault(AlamatPengguna $alamat)
    {
        abort_if($alamat->pengguna_id !== Auth::id(), 403);

        AlamatPengguna::where('pengguna_id', Auth::id())
            ->update(['is_default' => false]);

        $alamat->update(['is_default' => true]);

        return back()->with('success', 'Alamat utama diperbarui');
    }

    public function update(Request $request, AlamatPengguna $alamat)
    {
        abort_if($alamat->pengguna_id !== Auth::id(), 403);

        $request->validate([
            'alamat' => 'required',
            'label' => 'nullable|string|max:50',
            'no_telp' => 'required',
        ]);

        // Kalau diset sebagai default
        if ($request->has('is_default')) {
            AlamatPengguna::where('pengguna_id', Auth::id())
                ->update(['is_default' => false]);
        }

        $alamat->update([
            'label' => $request->label,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui');
    }

    public function destroy(AlamatPengguna $alamat)
    {
        abort_if($alamat->pengguna_id !== Auth::id(), 403);

        $alamat->delete();

        return back()->with('success', 'Alamat dihapus');
    }
}
