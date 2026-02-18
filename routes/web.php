<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {return view('welcome');});
Route::get('/dashboard', function () {return view('dashboard');});
Route::resource('tasks', TaskController::class);
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
