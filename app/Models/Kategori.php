<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = "kategoris";
    protected $primaryKey = "id";

    protected $fillable = [
        'nama_kategori', 
        'description'
    ];

    public function products()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}
