<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::firstOrCreate(
        ['username' => 'admin'],
        [
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]
    );
    }
}
