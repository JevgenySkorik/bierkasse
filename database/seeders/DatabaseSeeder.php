<?php

namespace Database\Seeders;

use App\Models\user;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        user::create([
            'name' => 'admin',
            'password' => Hash::make('Nsw223Sd'),
        ]);
    }
}
