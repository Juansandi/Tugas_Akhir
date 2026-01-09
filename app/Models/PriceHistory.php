<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;
use App\Models\ProductSize;
use App\Models\Pengguna;

class PriceHistory extends Model
{
     protected $fillable = [
        'produk_id',
        'product_size_id',
        'harga_lama',
        'harga_baru',
        'pengguna_id'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}
