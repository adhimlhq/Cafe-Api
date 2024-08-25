<?php

namespace Database\Seeders;

use App\Models\Cafe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CafeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cafe 1
        Cafe::create([
            'name' => 'Cafe Aroma',
            'address' => 'Jl. Sudirman No.1, Jakarta',
            'phone_number' => '+621234567890',
        ]);

        // Cafe 2
        Cafe::create([
            'name' => 'Cafe Delight',
            'address' => 'Jl. Thamrin No.2, Jakarta',
            'phone_number' => '+621234567891',
        ]);
    }
}
