<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "produks";
    protected $primaryKey = "id";

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'kategori_id',
        'stok',
        'image',
        'jenis',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'produk_id');
    }

}
