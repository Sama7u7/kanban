<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('tasks');
        }

        return back()->withErrors([
            'email' => 'Las credenciales que introduciste son incorrectas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario autenticado

        $request->session()->invalidate(); // Invalida la sesión existente

        $request->session()->regenerateToken(); // Genera un nuevo token CSRF para la sesión

        return redirect('/'); // Redirige al usuario a la página de inicio de sesión
    }
}
