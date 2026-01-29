<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna;
use App\Models\DetailPesanan;
use App\Models\Refund;
use App\Models\TugasKurir;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;

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
        'bukti_bayar',
        'waktu_bayar',
        'delivery_slot_id',
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

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'belum_dibayar'        => 'Belum Dibayar',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'diproses'            => 'Diproses',
            'dikirim'             => 'Dikirim',
            'diterima'            => 'Diterima',
            'selesai'             => 'Selesai',
            'dibatalkan'          => 'Dibatalkan',
            default               => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'belum_dibayar'        => 'bg-danger',
            'menunggu_konfirmasi' => 'bg-warning text-dark',
            'diproses'            => 'bg-primary',
            'dikirim'             => 'bg-info text-dark',
            'diterima'            => 'bg-secondary',
            'selesai'             => 'bg-success',
            'dibatalkan'          => 'bg-dark',
            default               => 'bg-light text-dark',
        };
    }
    
    public static function autoCancelExpired()
    {
        DB::transaction(function () {

            $pesanans = self::with('detail.size', 'detail.paket.detailPakets.size')
                ->where('status', 'belum_dibayar')
                ->where('created_at', '<=', now()->subHours(24))
                ->get();

            foreach ($pesanans as $pesanan) {

                foreach ($pesanan->detail as $item) {

                    // PRODUK BIASA
                    if ($item->type === 'produk' && $item->size) {
                        $item->size->increment('stok', $item->quantity);
                    }

                    // PAKET
                    if ($item->type === 'paket' && $item->paket) {
                        foreach ($item->paket->detailPakets as $detail) {
                            $detail->size->increment(
                                'stok',
                                $detail->quantity * $item->quantity
                            );
                        }
                    }
                }
                UserNotification::create([
                    'user_id' => $pesanan->user_id,
                    'tipe'    => 'pesanan_dibatalkan',
                    'pesan'   => 'Pesanan #' . $pesanan->id . ' dibatalkan karena belum dibayar.',
                    'url'     => route('pesanan.show', $pesanan->id),
                ]);

                // UPDATE STATUS (TIDAK DIHAPUS)
                $pesanan->update([
                    'status' => 'dibatalkan'
                ]);
            }

        });
    }

    public function deliverySlot()
    {
        return $this->belongsTo(DeliverySlot::class);
    }

}
