<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cashier User',
                'email' => 'cashier1@example.com',
                'password' => Hash::make('123'),
                'role' => 'cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cashier User',
                'email' => 'cashier2@example.com',
                'password' => Hash::make('123'),
                'role' => 'cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
