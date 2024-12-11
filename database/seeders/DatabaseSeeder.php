<?php

namespace Database\Seeders;

use App\Models\user;
use App\Models\product;
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
            'email' => 'jevgeny.skorik@gmail.com',
            'password' => Hash::make('Nsw223Sd'),
        ]);
        product::create(['name' => 'Ilguciema', 'price' => 1.5]);
        product::create(['name' => 'Salty chips', 'price' => 1]);
    }
}
