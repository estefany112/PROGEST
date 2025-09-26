<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $asistenteRole = Role::firstOrCreate(['name' => 'asistente']);

        // Usuario Administrador
        $admin = User::updateOrCreate(
            ['email' => 'admin@progest.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('1234'), 
                'estado' => 'activo',
            ]
        );
        $admin->assignRole($adminRole);

        // Usuario Asistente
        $asistente = User::updateOrCreate(
            ['email' => 'asistente@progest.com'],
            [
                'name' => 'Asistente',
                'password' => Hash::make('1234'),
                'estado' => 'activo',
            ]
        );
        $asistente->assignRole($asistenteRole);
    }
}
