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
        Schema::table('pesanans', function (Blueprint $table) {
            $table->foreignId('delivery_slot_id')
                  ->nullable()
                  ->after('alamat_pengiriman')
                  ->constrained('delivery_slots')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropForeign(['delivery_slot_id']);
            $table->dropColumn('delivery_slot_id');
        });
    }
};
