<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'erwan@gmail.com'],
            [
                'name' => 'Admin Erwan',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        // Create a test customer
        User::updateOrCreate(
            ['email' => 'customer@cookspace.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // Create a test cooker
        User::updateOrCreate(
            ['email' => 'cooker@cookspace.com'],
            [
                'name' => 'Test Cooker',
                'password' => Hash::make('password'),
                'role' => 'cooker',
            ]
        );
    }
}
