<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    protected $fillable = [
        'waktu_mulai', 
        'waktu_selesai'
        ];
    public $timestamps = false;
}
