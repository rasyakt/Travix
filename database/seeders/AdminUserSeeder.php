<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@travix.com'],
            [
                'name' => 'Admin Travix',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
                'phone' => '081234567890',
                'nationality' => 'Indonesia',
            ]
        );
    }
}
