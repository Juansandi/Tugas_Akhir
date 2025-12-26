<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPaket extends Model
{
    use HasFactory;

    protected $fillable = [
        'paket_id',
        'produk_id',
        'product_size_id',
        'quantity'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id');
    }
}
