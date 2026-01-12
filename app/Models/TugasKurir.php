<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pesanan;
use App\Models\Pengguna;

class TugasKurir extends Model
{
    protected $table = 'tugas_kurir';

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'status',
        'waktu_kirim',
        'bukti_kirim',
        'catatan_kurir',    
    ];

    protected $casts = [
        'waktu_kirim' => 'datetime',
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
