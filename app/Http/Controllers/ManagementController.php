<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class ManagementController extends Controller
{
    public function admin()
    {
        if(auth()->user()->role !== 'super_admin'){
            abort(403);
        }

        $users = Pengguna::where('role', 'admin')->get();
        return view('admin.pegawai.admin', compact('users'));
    }

    public function kurir()
    {
        if(auth()->user()->role !== 'super_admin'){
            abort(403);
        }

        $users = Pengguna::where('role', 'kurir')->get();
        return view('admin.pegawai.kurir', compact('users'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->role !== 'super_admin'){
            abort(403,'Hanya super admin yang dapat menambah admin atau kurir.');
        }
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

    public function toggleStatus($id)
    {
        // hanya super admin
        if(auth()->user()->role !== 'super_admin'){
            abort(403,'Hanya super admin yang dapat mengubah status pengguna.');
        }

        $user = Pengguna::findOrFail($id);

        // tidak boleh menonaktifkan super admin
        if ($user->role === 'super_admin') {
            abort(403);
        }

        // tidak boleh menonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Status pengguna berhasil diperbarui');
    }
}
