<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;



// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $password = env("ADMIN_PASSWORD", "password");

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.net',
            'password' => Hash::make($password),
            'role' => 'admin',
            'score' => 0,
        ]);
    }
}
