<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR PERMISOS BÁSICOS
        $permissions = [
            ['name' => 'manage_roles', 'description' => 'Gestionar roles y permisos'],
            ['name' => 'manage_users', 'description' => 'Gestionar usuarios y contraseñas'],
            ['name' => 'create_tasks', 'description' => 'Crear nuevas tareas'],
            ['name' => 'edit_tasks',   'description' => 'Editar tareas existentes'],
            ['name' => 'delete_tasks', 'description' => 'Eliminar tareas'],
            ['name' => 'view_all_tasks', 'description' => 'Ver tareas de todos los usuarios'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        // 2. CREAR ROLES
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrador']
        );

        $profesorRole = Role::updateOrCreate(
            ['name' => 'profesor'],
            ['display_name' => 'Profesor']
        );

        // 3. ASIGNAR PERMISOS A LOS ROLES
        // El Admin tiene TODO
        $allPermissionIds = Permission::all()->pluck('id');
        $adminRole->permissions()->sync($allPermissionIds);

        // El Profesor solo tareas y ver
        $profesorPermissionIds = Permission::whereIn('name', ['create_tasks', 'edit_tasks', 'view_all_tasks'])->pluck('id');
        $profesorRole->permissions()->sync($profesorPermissionIds);

        // 4. CREAR USUARIOS DE PRUEBA
        
        // El Administrador (Tú)
        $admin = User::updateOrCreate(
            ['email' => 'admin@taskify.com'],
            [
                'name' => 'Administrador Maestro',
                'password' => Hash::make('password'), // Cambia esto después
            ]
        );
        $admin->roles()->sync([$adminRole->id]);

        // Un ejemplo de Profesor (Como el de tu captura)
        $profe = User::updateOrCreate(
            ['email' => 'alberto@example.com'],
            [
                'name' => 'Alberto Samayoa Ramos',
                'password' => Hash::make('password'),
            ]
        );
        $profe->roles()->sync([$profesorRole->id]);

        $this->command->info('Seed completado: Usuarios y Roles creados con éxito.');
    }
}