<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PriceHistory;

class PriceHistoryController extends Controller
{
    public function index()
    {
        $histories = PriceHistory::with(['produk', 'size', 'pengguna'])
            ->latest()
            ->paginate(15);

        return view('admin.price_histories.index', compact('histories'));
    }
}
