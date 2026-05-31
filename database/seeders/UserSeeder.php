<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        User::create([
            'name' => 'Bagas',
            'email' => 'bagas@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Djanu',
            'email' => 'djanu@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Andika',
            'email' => 'andika@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Prasetyo',
            'email' => 'prasetyo@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Wawan',
            'email' => 'wawan@gmail.com',
            'password' => Hash::make('eren123'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}
