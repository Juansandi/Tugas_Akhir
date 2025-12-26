<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'produk_id',
        'paket_id',
        'product_size_id', 
        'quantity',
        'type'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

}
