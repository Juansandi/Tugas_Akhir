<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna;

class AlamatPengguna extends Model
{
    protected $table = 'alamat_pengguna';

    protected $fillable = [
        'pengguna_id',
        'label',
        'alamat',
        'no_telp',
        'is_default',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
    
}
