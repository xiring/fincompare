<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\Auth\Domain\Entities\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => 'password',
            ]);
        }
    }
}
