<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'              => 'Admin',
            'email'             => 'admin@test.com',
            'password'          => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Alice Dupont',
            'email'             => 'alice@test.com',
            'password'          => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name'              => 'Bob Martin',
            'email'             => 'bob@test.com',
            'password'          => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
