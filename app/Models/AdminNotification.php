<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'tipe',
        'pesan',
        'url',
        'is_read',
    ];

    // Cast otomatis kolom boolean
    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Optional: scope untuk notifikasi belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Optional: format waktu lebih ramah
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
