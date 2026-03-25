<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        User::create([
            'username' => 'diskominfo01',
            'password' => Hash::make('diskominfo2026'),
            'role' => 'admin'
        ]);

    }
}