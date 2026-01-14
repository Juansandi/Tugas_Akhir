<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id', 
        'user_id', 
        'alasan', 
        'bukti_foto',
        'metode_refund', 
        'nomor_tujuan', 
        'status', 
        'respon_admin',
        'refund_amount', 
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function pesanan() {
        return $this->belongsTo(Pesanan::class);
    }

    public function pengguna() {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    
}
