<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use App\Models\AlamatPengguna;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {   
    /** @var \App\Models\Pengguna $user */
    $user = Auth::user();

        // 🔄 SINKRON ALAMAT DEFAULT
        if ($user->alamat && $user->alamatPengguna()->count() === 0) {
            AlamatPengguna::create([
                'pengguna_id' => $user->id,
                'label' => 'Alamat Utama',
                'alamat' => $user->alamat,
                'no_telp' => $user->no_telp,
                'is_default' => true,
            ]);
        }

        // reload relasi alamat
        $user->load('alamatPengguna');

        return view('user.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        DB::table('pengguna')
            ->where('id', Auth::id()) // atau kolom primary key lainnya
            ->update([
                'username' => $request->username,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function editPassword()
    {
        return view('user.profile.edit_password'); // Buat view khusus form edit password
    }

    public function updatePassword(Request $request)
    {
        // Validasi password lama dan password baru
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed', // 'confirmed' cek ada input new_password_confirmation
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // Update password baru (hash terlebih dahulu)
        DB::table('pengguna')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->new_password),
            ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    } 

}
