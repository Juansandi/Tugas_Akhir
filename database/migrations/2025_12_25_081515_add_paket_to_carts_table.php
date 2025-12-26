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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->after('produk_id')
                ->constrained()->nullOnDelete();

            $table->enum('type', ['produk', 'paket'])
                ->default('produk')
                ->after('paket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['paket_id', 'type']);
        });
    }
};
