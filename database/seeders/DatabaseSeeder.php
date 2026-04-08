<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Call WilayahSeeder
        $this->call(WilayahSeeder::class);

        // Call BarangSeeder if exists
        if (class_exists(BarangSeeder::class)) {
            $this->call(BarangSeeder::class);
        }
    }
}
