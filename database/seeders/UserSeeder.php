<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the users table with default accounts.
     */
    public function run(): void
    {
        // Create Admin Dinkes account
        User::create([
            'username' => 'admin_dinkes',
            'password' => Hash::make('dinkeshebat'),
            'role' => 'admin_dinkes',
        ]);

        // Create Operator SPPG account
        User::create([
            'username' => 'sppgbeji',
            'password' => Hash::make('sppgbeji123'),
            'role' => 'operator_sppg',
        ]);
        User::create([
            'username' => 'sppgbeji2',
            'password' => Hash::make('beji123'),
            'role' => 'operator_sppg',
        ]);
    }
}
