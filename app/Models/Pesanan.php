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
        'alamat_pengiriman',
        'no_telp_pengiriman',
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

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
    
    public function tugasKurir()
    {
        return $this->hasOne(TugasKurir::class, 'pesanan_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function chatAdminUnreadForUser()
    {
        return $this->hasOne(Chat::class)
            ->where('type', 'admin')
            ->withCount([
                'messages as unread_count' => function ($q) {
                    $q->where('sender_type', 'admin')
                    ->where('is_read', false);
                }
            ]);
    }

    public function chatKurirUnreadForUser()
    {
        return $this->hasOne(Chat::class)
            ->where('type', 'kurir')
            ->withCount([
                'messages as unread_count' => function ($q) {
                    $q->where('sender_type', 'kurir')
                    ->where('is_read', false);
                }
            ]);
    }

    public function chatAdmin()
    {
        return $this->hasOne(Chat::class)
        ->where('type', 'admin')
        ->withCount([
            'messages as unread_count' => function ($q) {
                $q->where('sender_type', 'user')
                  ->where('is_read', false);
            }
        ]);
    }

    public function chatKurir()
    {
        return $this->hasOne(Chat::class)->where('type', 'kurir');
    }
}
