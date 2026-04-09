<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->hasPermission('view_all_tasks');

        // --- 1. KPIs (Global para Admin, Personal para Usuario) ---
        if ($isAdmin) {
            $totalTasks = Task::count();
            $completedTasks = Task::where('status', 'hecho')->count();
            $overdueTasks = Task::whereDate('due_date', '<', now())
                                ->whereNotIn('status', ['hecho', 'cancelado'])
                                ->count();
            $activeUsers = User::count();
        } else {
            $totalTasks = Task::where('responsible', $user->id)->orWhere('requester', $user->id)->count();
            $completedTasks = Task::where('status', 'hecho')
                                  ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))
                                  ->count();
            $overdueTasks = Task::whereDate('due_date', '<', now())
                                ->whereNotIn('status', ['hecho', 'cancelado'])
                                ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))
                                ->count();
            $activeUsers = 1; 
        }

        // --- 2. EL GRÁFICO (Corregido para Solicitantes) ---
        if ($isAdmin) {
            $statusCounts = [
                'por_hacer' => Task::where('status', 'por_hacer')->count(),
                'haciendo'  => Task::where('status', 'haciendo')->count(),
                'hecho'     => $completedTasks,
                'cancelado' => Task::where('status', 'cancelado')->count(),
            ];
        } else {
            // CORRECCIÓN: Ahora cuenta tanto lo que tiene que hacer COMO lo que pidió
            $statusCounts = [
                'por_hacer' => Task::where('status', 'por_hacer')
                                   ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))->count(),
                'haciendo'  => Task::where('status', 'haciendo')
                                   ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))->count(),
                'hecho'     => $completedTasks,
                'cancelado' => Task::where('status', 'cancelado')
                                   ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))->count(),
            ];
        }

        // --- 3. ATENCIÓN REQUERIDA (Prioridad: Mis tareas > Tareas del sistema) ---
        if ($isAdmin) {
            $urgentTasks = Task::with(['responsibleUser', 'requesterUser'])
                ->whereNotIn('status', ['hecho', 'cancelado'])
                ->orderByRaw("CASE WHEN responsible = {$user->id} THEN 1 ELSE 2 END")
                ->orderBy('due_date', 'asc')
                ->take(5) // <-- Si alguna vez quieres ver más de 5 tareas, cambia o quita este número
                ->get();
        } else {
            $urgentTasks = Task::with(['responsibleUser', 'requesterUser'])
                ->where(fn($q) => $q->where('responsible', $user->id)->orWhere('requester', $user->id))
                ->whereNotIn('status', ['hecho', 'cancelado'])
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get();
        }

        // Asegúrate de que la vista se llame exactamente como la tienes en tus carpetas.
        // En el código que me mandaste dice 'dashboard.index', si tu vista se llama
        // 'admin.dashboard', cámbialo aquí abajo.
        return view('dashboard.index', compact(
            'totalTasks', 'completedTasks', 'activeUsers', 'overdueTasks', 'statusCounts', 'urgentTasks'
        ));
    }
}