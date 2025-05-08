<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:promos',
            'persen_diskon' => 'required|integer|min:0|max:100',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:mulai',
        ]);

        Promo::create($request->all());

        return redirect()->route('promos.index')->with('success', 'Promo ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'nama' => 'required',
            'kode' => 'required|unique:promos,kode,' . $promo->id,
            'persen_diskon' => 'required|integer|min:0|max:100',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:mulai',
        ]);

        $promo->update($request->all());

        return redirect()->route('promos.index')->with('success', 'Promo diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('promos.index')->with('success', 'Promo dihapus.');
    }
}
