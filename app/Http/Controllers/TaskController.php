<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        $user = auth()->user();

        // 1. FILTRADO DE TAREAS POR PERMISOS
        $query = Task::with(['responsibleUser', 'requesterUser']);

        // Volvemos a tu método personalizado (sin el "To")
        if (!$user->hasPermission('view_all_tasks')) {
            $query->where(function ($q) use ($user) {
                // Mantenemos la búsqueda por ID por la validación
                $q->where('responsible', $user->id)
                  ->orWhere('requester', $user->id);
            });
        }

        $tasks = $query->latest()->paginate(15);

        // 2. OBTENER USUARIOS PARA LOS DROPDOWNS
        // Volvemos a tu lógica original buscando en tu relación de roles
        $itUsers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'it']); 
        })->get();

        $nonItUsers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', [
                'padre_familia', 'profesor', 'seccion_prim',
                'seccion_sec', 'seccion_prep', 'seccion_pres'
            ]);
        })->get();

        return view('admin.dashboard', compact('tasks', 'itUsers', 'nonItUsers'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            // Validamos que se guarde el ID y exista en la tabla users
            'responsible' => 'nullable|integer|exists:users,id',
            'requester'   => 'nullable|integer|exists:users,id',
            'due_date'    => 'nullable|date',
            'status'      => 'required|in:por_hacer,haciendo,hecho,cancelado',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Actividad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task) // Usamos Route Model Binding
    {
        // Terminamos la validación que quedó a medias
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            // Unificamos con store() para exigir el mismo tipo de dato
            'responsible' => 'nullable|integer|exists:users,id',
            'requester'   => 'nullable|integer|exists:users,id',
            'due_date'    => 'nullable|date',
            'status'      => 'required|in:por_hacer,haciendo,hecho,cancelado',
        ]);

        // Usamos $validated en lugar de $request->all() para mayor seguridad
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task) // Usamos Route Model Binding
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada con éxito.');
    }
}