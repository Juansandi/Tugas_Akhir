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
             $table->foreignId('product_size_id')
                  ->after('produk_id')
                  ->constrained('product_sizes')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropForeign(['product_size_id']);
            $table->dropColumn('product_size_id');
        });
    }
};
