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
            $tasks = Task::paginate(15);
          // 2. Usuarios de IT (para el campo Responsable)
            $itUsers = User::where('role', 'it')->get();

        // 3. Usuarios Solicitantes (para el campo Solicitante)
            $nonItUsers = User::whereIn('role', [
                'padre_familia', 'profesor', 'seccion_prim',
                'seccion_sec', 'seccion_prep', 'seccion_pres'
            ])->get();
        return view('dashboard', compact('tasks', 'itUsers', 'nonItUsers'));
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
            'responsible' => 'nullable|string|max:255',
            'requester' => 'nullable|string|max:255',
            'due_date'    => 'nullable|date',
            'status'      => 'required|in:por_hacer,haciendo,hecho,cancelado',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Actividad creada exitosamente.');
    }
        /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsible' => 'required|string|max:255',
            'requester' => 'required|string|max:255',
            'due_date'    => 'nullable|date',
            'status'      => 'required|in:por_hacer,haciendo,hecho,cancelado',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarea = Task::findOrFail($id);
        $tarea->delete();

         return redirect()->route('tasks.index')->with('success', 'Tarea eliminada con éxito.');
    }
}
