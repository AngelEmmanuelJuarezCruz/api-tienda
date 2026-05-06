<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'dueno@apitienda.local'],
            [
                'name' => 'Dueno Tienda',
                'password' => Hash::make('password123'),
                'rol' => 'dueno',
                'activo' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'encargado@apitienda.local'],
            [
                'name' => 'Encargado General',
                'password' => Hash::make('password123'),
                'rol' => 'encargado',
                'activo' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'cajero@apitienda.local'],
            [
                'name' => 'Cajero Principal',
                'password' => Hash::make('password123'),
                'rol' => 'cajero',
                'activo' => true,
            ]
        );
    }
}
