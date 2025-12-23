<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasKurir extends Model
{
    protected $table = 'tugas_kurir';

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'status',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }

    public function kurir()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id');
    }
}
