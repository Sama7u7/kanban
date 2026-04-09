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
        $users = User::with('roles')->paginate(15);
        $roles = Role::all(); 
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    // Manejar el envío del formulario y crear el usuario
    public function store(Request $request)
    {
        // Actualizado para validar un array de roles
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles'    => 'required|array', // Validamos que sea un array
            'roles.*'  => 'exists:roles,id', // Validamos que cada ID exista
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Usamos sync para asignar los múltiples roles desde el array
        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Actualiza los datos de un usuario
    public function update(Request $request, $id)
    {
        // Actualizado para validar un array de roles
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'roles'   => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $usuario = User::findOrFail($id);

        $usuario->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Sincronización múltiple: Borra los anteriores y pone los nuevos seleccionados
        $usuario->roles()->sync($request->roles);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6',
            ]);
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
        // Desvinculamos todos sus roles
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