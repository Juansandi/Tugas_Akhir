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
        Schema::create('detail_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_size_id')->nullable()->constrained('product_sizes')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->timestamps();
            $table->unique(['paket_id', 'produk_id', 'product_size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pakets');
    }
};
