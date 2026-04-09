<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

// --- RUTAS PÚBLICAS ---
Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware(['auth'])->group(function () {

    // Gestión de Tareas (Accesible para todos los logueados, el filtrado se hace en el Controller)
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

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