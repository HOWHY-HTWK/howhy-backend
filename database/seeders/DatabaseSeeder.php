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

        \App\Models\User::factory()->create([
            'name' => 'Alex',
            'email' => 'alex@alex.com',
            'password' => Hash::make('password'),
        ]);
    }
}
