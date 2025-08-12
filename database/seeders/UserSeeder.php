<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'mjartwashere',
            'username' => 'admin',
            'email' => 'admin@loremipsum.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'admin',
            'avatar' => 'https://i.pravatar.cc/40?img=1',
        ]);

        User::create([
            'name' => 'loremipsum User',
            'username' => 'user',
            'email' => 'user@loremipsum.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=2',
        ]);

        User::create([
            'name' => 'Jay Doe',
            'username' => 'jaydoe',
            'email' => 'jay@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=3',
        ]);

        User::create([
            'name' => 'Anna Smith',
            'username' => 'annasmith',
            'email' => 'anna@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=4',
        ]);

        User::create([
            'name' => 'Chris Green',
            'username' => 'chrisgreen',
            'email' => 'chris@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=5',
        ]);

        User::create([
            'name' => 'Emily Rose',
            'username' => 'emilyrose',
            'email' => 'emily@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Reported',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=6',
        ]);

        User::create([
            'name' => 'David Lee',
            'username' => 'davidlee',
            'email' => 'david@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=7',
        ]);

        User::create([
            'name' => 'Sophia Kim',
            'username' => 'sophiakim',
            'email' => 'sophia@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=8',
        ]);

        User::create([
            'name' => 'Lucas Park',
            'username' => 'lucaspark',
            'email' => 'lucas@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=9',
        ]);

        User::create([
            'name' => 'Nina Patel',
            'username' => 'ninapatel',
            'email' => 'nina@example.com',
            'password' => Hash::make('1234'),
            'status' => 'Active',
            'role' => 'user',
            'avatar' => 'https://i.pravatar.cc/40?img=10',
        ]);
    }
}
