<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Produk;
use App\Models\Pengguna; // atau App\Models\User jika pakai model default
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkList = Produk::all();
        $penggunaList = \App\Models\Pengguna::all(); // Ganti ke User jika pakai model User

        foreach ($produkList as $produk) {
            // Setiap produk diberi 3-5 ulasan acak dari pengguna
            $reviewers = $penggunaList->random(rand(3, 5));

            foreach ($reviewers as $user) {
                // Pastikan tidak review ganda
                if (!Review::where('user_id', $user->id)->where('produk_id', $produk->id)->exists()) {
                    Review::create([
                        'user_id' => $user->id,
                        'produk_id' => $produk->id,
                        'rating' => rand(1, 5),
                        'comment' => fake()->sentence(rand(6, 15)),
                    ]);
                }
            }
        }
    }
}
