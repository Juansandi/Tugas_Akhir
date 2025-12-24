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
        'product_size_id', 
        'quantity'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }

}
