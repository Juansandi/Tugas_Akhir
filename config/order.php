<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Waktu Auto Cancel Pesanan
    |--------------------------------------------------------------------------
    |
    | Satuan: JAM
    | Bisa diubah lewat .env tanpa ubah kode
    |
    */

    'expire_transfer_hours' => env('ORDER_EXPIRE_TRANSFER', 24),

    'expire_cod_hours' => env('ORDER_EXPIRE_COD', 24),

];
