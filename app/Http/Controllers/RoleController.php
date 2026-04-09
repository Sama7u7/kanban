<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Muestra la lista de roles y permisos
     */
    public function index()
    {
        // Traemos todos los roles con sus permisos (eager loading para que sea rápido)
        $roles = Role::with('permissions')->get();
        
        // También traemos todos los permisos para que el modal de "Crear" pueda mostrarlos
        $permissions = Permission::all();

        // Asegúrate de que el nombre de la vista coincida con tu archivo
        // Ej: resources/views/roles/index.blade.php -> 'roles.index'
        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Guarda un nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|unique:roles,display_name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create([
            'display_name' => $request->display_name,
            'name' => Str::slug($request->display_name)
        ]);

        $role->permissions()->sync($request->permissions);

        return back()->with('success', 'Rol creado con éxito');
    }
}