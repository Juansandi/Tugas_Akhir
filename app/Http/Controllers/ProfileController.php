<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
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

}
