<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

   public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $pengguna = Pengguna::where('username', $request->username)->first();

        if ($pengguna && Hash::check($request->password, $pengguna->password)) {
            if (!$pengguna->is_active) {
                return back()->withErrors([
                    'login' => 'Akun Anda telah dinonaktifkan oleh administrator.'
                ]);
            }
            
            Auth::login($pengguna);
            $request->session()->regenerate();

            if (in_array($pengguna->role, ['admin','super_admin'])) {
                return redirect('/admin/dashboard');
            }

            if ($pengguna->role === 'kurir') {
                return redirect('/kurir/dashboard');
            }

            return redirect('/home');
        }

        return back()->withErrors([
            'login' => 'Nama pengguna atau kata sandi salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
