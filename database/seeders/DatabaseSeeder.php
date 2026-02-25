<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Padre de familia',
            'email' => 'padre@example.com',
            'password' => 'padre1',
            'role' => 'padre_familia'
        ]);
        User::factory()->create([
            'name' => 'Profesor',
            'email' => 'profe@example.com',
            'password' => 'profe1',
            'role' => 'profesor'
        ]);
        User::factory()->create([
            'name' => 'Seccion primaria',
            'email' => 'primaria@example.com',
            'password' => 'primaria1',
            'role' => 'seccion_prim'
        ]);
        User::factory()->create([
            'name' => 'Seccion secundaria',
            'email' => 'secundaria@example.com',
            'password' => 'secundaria1',
            'role' => 'seccion_sec'
        ]);
        User::factory()->create([
            'name' => 'Seccion preparatoria',
            'email' => 'prepa@example.com',
            'password' => 'prepa1',
            'role' => 'seccion_prep'
        ]);
        User::factory()->create([
            'name' => 'Alberto Samayoa Ramos',
            'email' => 'iting@example.com',
            'password' => 'pass',
            'role' => 'it'
        ]);
    }
}
