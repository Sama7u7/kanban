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
        public function index(Request $request)
    {
    $user = auth()->user();
    $query = Task::with(['responsibleUser', 'requesterUser']);

    // --- 1. FILTRADO POR SEGURIDAD (Tus reglas originales) ---
    if (!$user->hasPermission('view_all_tasks')) {
        $query->where(function ($q) use ($user) {
            $q->where('responsible', $user->id)
              ->orWhere('requester', $user->id);
        });
    }

    // --- 2. FILTROS DINÁMICOS (Lo nuevo) ---

    // Búsqueda por texto (Título o Descripción)
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // Por Estatus
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Por Responsable
    if ($request->filled('responsible_id')) {
        $query->where('responsible', $request->responsible_id);
    }

    // Obtenemos resultados
    $tasks = $query->latest()->paginate(15)->withQueryString(); // withQueryString mantiene los filtros al cambiar de página

    // Usuarios para los selectores (tus queries actuales)
    $itUsers = User::whereHas('roles.permissions', function ($q) {
        $q->where('permissions.name', 'atender_tareas'); 
    })->get();

    $nonItUsers = User::whereHas('roles.permissions', function ($q) {
        $q->where('permissions.name', 'solicitar_tareas');
    })->get();

    return view('tasks.index', compact('tasks', 'itUsers', 'nonItUsers'));
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
    // 1. Por defecto, el solicitante es quien está logueado
    $requester_id = auth()->id();

    // 2. Pero si es Admin y mandó un ID diferente en el formulario, se lo cambiamos
    if (auth()->user()->hasPermission('view_all_tasks') && $request->filled('requester_id')) {
        $requester_id = $request->input('requester_id');
    }

    // 3. El responsable (si tienen permiso para asignarlo)
    $responsible_id = $request->input('responsible', null);

    Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'requester' => $requester_id, // <-- Ahora es dinámico y seguro
        'responsible' => $responsible_id,
        'status' => 'por_hacer', 
    ]);

    return redirect()->back()->with('success', 'Tarea creada con éxito.');
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
    public function update(Request $request, Task $task)
    {
    $user = auth()->user();
    $isAdminOrIT = $user->hasPermission('view_all_tasks') || $user->hasPermission('atender_tareas');

    // 1. Validamos los datos básicos
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'required|date',
        'status' => 'required|string',
    ]);

    // 2. SEGURIDAD DE ESTATUS: 
    // Si NO es Admin/IT y el estatus actual ya es 'haciendo' o 'hecho', bloqueamos cualquier cambio de estatus.
    if (!$isAdminOrIT && in_array($task->status, ['haciendo', 'hecho'])) {
        // Solo dejamos actualizar título y descripción, pero forzamos el estatus original
        $validated['status'] = $task->status; 
    }

    // 3. SEGURIDAD DE RESPONSABLE:
    // Solo permitimos cambiar el responsable si el usuario tiene permiso.
    if ($isAdminOrIT) {
        $task->responsible = $request->input('responsible');
    }
    // Si no tiene permiso, ni siquiera tocamos el campo $task->responsible

    // 4. Actualizamos el resto de los campos
    $task->title = $validated['title'];
    $task->description = $validated['description'];
    $task->due_date = $validated['due_date'];
    $task->status = $validated['status'];
    
    $task->save();

    return redirect()->route('tasks.index')->with('success', 'Tarea actualizada correctamente.');
    }
    /**
     * Actualiza únicamente el estatus de la tarea (usado por el Kanban)
     */
/**
     * Actualiza únicamente el estatus de la tarea (usado por el Kanban)
     */
        public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();
        $isAdminOrIT = $user->hasPermission('view_all_tasks') || $user->hasPermission('atender_tareas');

        // 1. Validamos que el estatus enviado sea válido
        $request->validate([
            'status' => 'required|in:por_hacer,haciendo,hecho,cancelado'
        ]);

        // 2. SEGURIDAD: Si es un solicitante puro...
        if (!$isAdminOrIT) {
            // A. No puede mover tareas que ya estén en proceso o terminadas
            if (in_array($task->status, ['haciendo', 'hecho'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes mover tareas que ya están en proceso.'
                ], 403);
            }

            // B. Solo puede mover entre 'por_hacer' y 'cancelado'
            if (!in_array($request->status, ['por_hacer', 'cancelado'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo puedes reactivar o cancelar tus tareas.'
                ], 403);
            }
        }

        // 3. Si pasó los filtros, actualizamos
        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Estatus actualizado'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Task $task)
{
    // Solo el Admin Maestro puede borrar físicamente una tarea
    if (!auth()->user()->hasPermission('view_all_tasks')) {
        return abort(403, 'No tienes permisos para eliminar tareas. Si cometiste un error, por favor cancélala.');
    }

    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'La tarea ha sido eliminada del sistema.');
}
}