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
            ['email' => 'admin@technodim.local'],
            [
                'name' => 'TechnoDim Admin',
                'role' => User::ROLE_ADMIN,
                'phone' => '380501112233',
                'password' => Hash::make('TechnoDim!2026'),
            ],
        );
        if ($this->command) {
            $this->command->info('Seeded admin: admin@technodim.local / TechnoDim!2026');
        }
    }
}
