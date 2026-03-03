<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAccountsSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@ccs.local'],
            [
                'full_name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('SuperAdmin2026'),
                'role' => 'super_admin',
                'is_active' => true,
                'force_password_change' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@ccs.local'],
            [
                'full_name' => 'School Administrator',
                'username' => 'adminuser',
                'password' => Hash::make('AdminUser2026'),
                'role' => 'admin',
                'is_active' => true,
                'force_password_change' => true,
            ]
        );
    }
}
