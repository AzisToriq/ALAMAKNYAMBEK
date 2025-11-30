<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Owner (Super Admin)
        User::create([
            'name' => 'Owner Pos',
            'email' => 'owner@pos.com',
            'password' => Hash::make('password'), // Password default
            'role' => 'owner',
            'is_active' => true,
        ]);

        // 2. Akun Staff Admin (Kepala Toko)
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 3. Akun Kasir
        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }
}