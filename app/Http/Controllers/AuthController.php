<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'admin') {
            // Si es admin, lo mandamos al dashboard de admin.
            return redirect()->intended('admin');
            }
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'user') {
            // Si es admin, lo mandamos al dashboard de admin.
            return redirect()->intended('user');
            }
        }
        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario autenticado

        $request->session()->invalidate(); // Invalida la sesión existente

        $request->session()->regenerateToken(); // Genera un nuevo token CSRF para la sesión

        return redirect('/'); // Redirige al usuario a la página de inicio de sesión
    }
}
