<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Menu 1 untuk Cafe Aroma
         Menu::create([
            'name' => 'Espresso',
            'price' => 25000.00,
            'is_Recommendation' => true,
            'cafe_id' => 1, // ID cafe yang diasumsikan sudah ada di database
        ]);

        // Menu 2 untuk Cafe Delight
        Menu::create([
            'name' => 'Latte',
            'price' => 30000.00,
            'is_Recommendation' => false,
            'cafe_id' => 2, // ID cafe yang diasumsikan sudah ada di database
        ]);
    }
}
