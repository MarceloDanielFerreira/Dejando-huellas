<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar el rol 'admin', asume que ya fue creado
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('El rol admin no existe. Ejecuta primero RoleSeeder.');
            return;
        }

        // Crear o actualizar usuario admin
        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@huellas.com')],
            [
                'name' => env('ADMIN_NAME', 'Administrador'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Administrador1')),
            ]
        );

        // Asignar rol admin si no lo tiene
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }
    }
}
