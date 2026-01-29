<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliverySlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('delivery_slots')->insert([
            ['waktu_mulai' => '08:00:00', 'waktu_selesai' => '09:00:00'],
            ['waktu_mulai' => '10:00:00', 'waktu_selesai' => '11:00:00'],
            ['waktu_mulai' => '13:00:00', 'waktu_selesai' => '14:00:00'],
            ['waktu_mulai' => '16:00:00', 'waktu_selesai' => '17:00:00'],
        ]);
    }
}
