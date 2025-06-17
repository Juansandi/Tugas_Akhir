<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoIdToPesananTable extends Migration
{
    public function up()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->unsignedBigInteger('promo_id')->nullable()->after('metode_pembayaran');

            // Jika ingin menambahkan foreign key constraint ke tabel promos
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('set null');
            
            // Jika juga ingin menambahkan kolom diskon_dari_promo
            $table->decimal('diskon_dari_promo', 12, 2)->default(0)->after('poin_digunakan');
        });
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn('promo_id');
            $table->dropColumn('diskon_dari_promo');
        });
    }
}
