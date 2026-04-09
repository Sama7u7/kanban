<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;

// --- RUTAS PÚBLICAS ---
Route::get('/', function () {
    // Si el usuario ya inició sesión, lo redirigimos al dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    // Si es un invitado, le mostramos el formulario de login
    return view('welcome');
})->name('login'); // <-- CAMBIO 1: El middleware 'auth' buscará exactamente este nombre para redirigir a los no logueados.

Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post'); // <-- CAMBIO 2: Le cambiamos el nombre a la ruta POST para que no haya conflictos.
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware(['auth'])->group(function () {

    // Ruta Principal del Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // GESTIÓN DE TAREAS 
    // ==========================================
    // 1. Ruta para que el Kanban guarde el estatus vía AJAX (Debe ir ANTES del resource)
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    
    // 2. Rutas estándar (index, store, update, destroy)
    Route::resource('tasks', TaskController::class);


    // ==========================================
    // GESTIÓN DE USUARIOS (Solo con permiso)
    // ==========================================
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->hasPermission('manage_users')) {
            return $next($request);
        }
        abort(403, 'No tienes permiso para gestionar usuarios.');
    }], function () {
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    });

    // ==========================================
    // GESTIÓN DE ROLES (Solo con permiso)
    // ==========================================
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->hasPermission('manage_roles')) {
            return $next($request);
        }
        abort(403, 'No tienes permiso para gestionar roles.');
    }], function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

});