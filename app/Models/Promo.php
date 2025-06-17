<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{

    use HasFactory;

    public $timestamps = false;
    protected $table = "promos";
    protected $primaryKey = "id";

    protected $fillable = [
        'nama_promo',
        'kode_promo',
        'deskripsi',
        'diskon',
        'mulai',
        'akhir'
    ];
}
