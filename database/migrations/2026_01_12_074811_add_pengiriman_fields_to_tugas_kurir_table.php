<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_kurir', function (Blueprint $table) {
            $table->timestamp('waktu_kirim')->nullable()->after('status');
            $table->string('bukti_kirim')->nullable()->after('waktu_kirim');
            $table->text('catatan_kurir')->nullable()->after('bukti_kirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_kurir', function (Blueprint $table) {
            $table->dropColumn(['waktu_kirim', 'bukti_kirim', 'catatan_kurir']);
        });
    }
};
