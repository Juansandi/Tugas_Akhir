<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $pengguna = Pengguna::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat ?? null,
            'no_telp' => $request->no_telp ?? null,
            'jumlah_poin' => 0,
            'role' => 'user',
        ]);


        Auth::login($pengguna);

        return redirect()->route('login'); ;
    }
}
