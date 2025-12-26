<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'harga_paket',
        'image',
        'status'
    ];

    public function detailPakets()
    {
        return $this->hasMany(DetailPaket::class);
    }
}
