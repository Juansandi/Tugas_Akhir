<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 
        'produk_id', 
        'rating', 
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
