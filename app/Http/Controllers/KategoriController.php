<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;


class KategoriController extends Controller
{
    public function index()
    {
        $categories = Kategori::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|unique:kategoris,nama_kategori',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true], 200);
    }

    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Kategori $category)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
