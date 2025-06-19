<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id', 
        'tipe', 
        'pesan', 
        'url', 
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
