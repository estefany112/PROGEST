<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesYUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $asistenteRole = Role::firstOrCreate(['name' => 'asistente']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'estado' => 'activo',
            ]
        );

        // Asignar rol admin al usuario
        $admin->syncRoles([$adminRole]);

        // Crear usuario asistente
        $asistente = User::firstOrCreate(
            ['email' => 'asistente@gmail.com'],
            [
                'name' => 'Asistente',
                'password' => bcrypt('asistente123'),
                'estado' => 'activo',
                'tipo' => 'asistente',
            ]
        );

        // Asignar rol asistente al usuario
        $asistente->syncRoles([$asistenteRole]);
    }
}
