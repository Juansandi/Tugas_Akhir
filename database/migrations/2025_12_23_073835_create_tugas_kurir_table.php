<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tugas_kurir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('user_id'); // kurir
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();

            // FK ke pesanans.id
            $table->foreign('pesanan_id')
                  ->references('id')
                  ->on('pesanans')
                  ->onDelete('cascade');

            // FK ke pengguna.id
            $table->foreign('user_id')
                  ->references('id')
                  ->on('pengguna')
                  ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_kurir');
    }
};
