<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'pesanan_id',
        'type',
        'is_active'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function messages()
    {
        return $this->hasMany(DetailChat::class);
    }
}
