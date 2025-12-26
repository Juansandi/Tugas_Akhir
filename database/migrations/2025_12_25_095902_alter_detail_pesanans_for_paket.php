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
        Schema::table('detail_pesanans', function (Blueprint $table) {

            // ubah jadi nullable
            $table->unsignedBigInteger('produk_id')->nullable()->change();
            $table->unsignedBigInteger('product_size_id')->nullable()->change();

            // tambah paket
            $table->unsignedBigInteger('paket_id')
                ->nullable()
                ->after('product_size_id');

            // tipe item
            $table->enum('type', ['produk', 'paket'])
                ->after('price');

            // foreign key paket
            $table->foreign('paket_id')
                ->references('id')
                ->on('pakets')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {

            $table->dropForeign(['paket_id']);
            $table->dropColumn(['paket_id', 'type']);

            $table->unsignedBigInteger('produk_id')->nullable(false)->change();
            $table->unsignedBigInteger('product_size_id')->nullable(false)->change();
        });
    }
};
