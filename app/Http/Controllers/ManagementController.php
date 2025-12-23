<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class ManagementController extends Controller
{
    public function admin()
    {
        $users = Pengguna::where('role', 'admin')->get();
        return view('admin.pegawai.admin', compact('users'));
    }

    public function kurir()
    {
        $users = Pengguna::where('role', 'kurir')->get();
        return view('admin.pegawai.kurir', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:pengguna',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,kurir',
        ]);

        Pengguna::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
    }
}
