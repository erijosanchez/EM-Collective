<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@emcollective.pe'],
            [
                'name'              => 'Administrador',
                'email'             => 'admin@emcollective.pe',
                'password'          => Hash::make('Admin123!'),
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin creado: admin@emcollective.pe / Admin123!');
    }
}
