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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('pengguna')->onDelete('cascade');
            $table->text('alasan');
            $table->string('bukti_foto')->nullable();
            $table->string('metode_refund');
            $table->string('nomor_tujuan');
            $table->enum('status', ['diajukan', 'ditolak', 'disetujui'])->default('diajukan');
            $table->text('respon_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
