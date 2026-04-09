<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Función para listar los usuarios
    public function index()
    {
        // Cargamos la relación roles para evitar el problema de celdas vacías
        $users = User::with('roles')->paginate(15);
        $roles = Role::all(); 
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    // Manejar el envío del formulario y crear el usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id', // Validamos que el ID del rol exista en la DB
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // VINCULACIÓN: Insertar en la tabla role_user
        $user->roles()->attach($request->role_id);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Actualiza los datos de un usuario
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id', // Validamos contra la tabla roles, no contra el string viejo
        ]);

        $usuario = User::findOrFail($id);

        // Actualizar campos básicos del usuario
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // SINCRONIZACIÓN: Actualizar el rol en la tabla intermedia
        $usuario->roles()->sync([$request->role_id]);

        // Si se envió una contraseña desde el modal (opcional)
        if ($request->filled('password')) {
            $usuario->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    // Elimina un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Desvinculamos roles antes de borrar para evitar basura en role_user
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Contraseña reseteada exitosamente.');
    }
}