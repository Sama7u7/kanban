<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
        // Mostrar el formulario para crear un usuario
        public function create()
        {
        //Manejado con Alpine
        }

        // Manejar el envío del formulario y crear el usuario
        public function store(Request $request)
        {
            // Validar los datos del formulario
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Crear un nuevo usuario
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),  // Hashear la contraseña
            ]);

            // Redirigir a la página de usuarios con un mensaje de éxito
            return redirect()->route('usuarios.create')->with('success', 'Usuario creado exitosamente.');
        }
    // Función para listar los usuarios
        public function index()
        {
            $users = User::paginate(15);
            return view('usersDashboard', compact('users'));
        }

        // Muestra el formulario para editar un usuario
        public function edit($id)
        {
        //Manejado con Alpine
        }

     // Actualiza los datos de un usuario
        public function update(Request $request, $id)
        {
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $usuario = User::findOrFail($id);

         // Si se envía una contraseña no vacía, actualízala
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $usuario->password = bcrypt($request->input('password'));
        }

         // Actualizar otros campos
        $usuario->update($request->except('password'));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
        }


        // Elimina un usuario
        public function destroy($id)
        {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'Tarea eliminada con éxito.');
        }
}
