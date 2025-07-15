<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'mjartwashere',
            'username' => 'admin',
            'email' => 'admin@loremipsum.com',
            'password' => Hash::make('1234'),
            'isAdmin' => true,
        ]);

        User::create([
            'name' => 'loremipsum User',
            'username' => 'user',
            'email' => 'user@loremipsum.com',
            'password' => Hash::make('1234'),
            'isAdmin' => false,
        ]);
    }
}
