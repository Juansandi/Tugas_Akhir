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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('pengguna')->onDelete('cascade');
            $table->decimal('total', 15, 2);
            $table->string('status')->default('pending'); // pending, dibayar, dikirim, selesai
            $table->string('metode_pembayaran')->nullable(); // COD, Transfer, dll
            $table->string('no_resi')->nullable(); // diisi admin setelah pengiriman
            $table->integer('poin_diperoleh')->default(0);
            $table->integer('poin_digunakan')->default(0);
            $table->decimal('diskon_dari_poin', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
