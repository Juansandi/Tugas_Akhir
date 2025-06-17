<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'metode_pembayaran',
        'no_resi',
        'poin_diperoleh',
        'poin_digunakan',
        'promo_id',
        'diskon_dari_poin',
        'diskon_dari_promo',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}
