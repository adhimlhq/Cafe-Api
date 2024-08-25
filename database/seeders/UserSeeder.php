<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Membuat Superadmin
         User::create([
            'username' => 'superadmin',
            'fullname' => 'Super Admin',
            'password' => Hash::make('password123'), // Gantilah password sesuai keinginan
            'role' => 'superadmin',
        ]);

        // Membuat Owner
        User::create([
            'username' => 'owner1',
            'fullname' => 'Owner One',
            'password' => Hash::make('password123'), // Gantilah password sesuai keinginan
            'role' => 'owner',
        ]);

        // Membuat Manager
        User::create([
            'username' => 'manager1',
            'fullname' => 'Manager One',
            'password' => Hash::make('password123'), // Gantilah password sesuai keinginan
            'role' => 'manager',
        ]);

        // Membuat pengguna tanpa role (untuk uji coba)
        User::create([
            'username' => 'user1',
            'fullname' => 'User One',
            'password' => Hash::make('password123'), // Gantilah password sesuai keinginan
            'role' => null, // Pengguna tanpa peran khusus
        ]);
    }
}
