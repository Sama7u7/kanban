@extends('layouts.test')

@section('content')
    <h1 class="text-4xl font-mono pb-8">Tareas</h1>

    <div class="grid grid-cols-3 gap-4 border rounded-xl border-gray-300 px-6 py-4">
        <div class="mt-8 text-center col-span-3">

            {{-- Botón que abre el modal --}}
            <button onclick="document.getElementById('modalCrear').classList.remove('hidden')"
                    class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                Crear una actividad
            </button>


            <table class="border-separate border-spacing-4 w-full mt-4">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Estatus</th>
                        <th>Responsable</th>
                        <th>Fecha creación</th>
                        <th>Fecha límite</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td class="text-left">{{ $task->title }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'por_hacer' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                        'haciendo'  => 'bg-blue-100 text-blue-700 border border-blue-300',
                                        'hecho'     => 'bg-green-100 text-green-700 border border-green-300',
                                        'cancelado' => 'bg-red-100 text-red-700 border border-red-300',
                                    ];
                                    $etiquetas = [
                                        'por_hacer' => 'Por hacer',
                                        'haciendo'  => 'Haciendo',
                                        'hecho'     => 'Hecho',
                                        'cancelado' => 'Cancelado',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badges[$task->status] }}">
                                    {{ $etiquetas[$task->status] }}
                                </span>
                            </td>
                            <td>{{ $task->responsible }}</td>
                            <td>{{ $task->created_at->format('d/m/Y') }}</td>
                            <td>{{ $task->due_date->format('d/m/Y') }}</td>
                            <td class="text-center flex items-center justify-center gap-3 py-2">

                                {{-- Botón Editar --}}

                                <a onclick="abrirModalEditar({{ $task->id }}, @js($task->title), @js($task->description), @js($task->responsible), '{{ $task->due_date?->format('Y-m-d') }}', @js($task->status))"
                                class="text-indigo-400 hover:text-indigo-600 cursor-pointer" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.5 1.5-4.5L16.862 3.487z"/>
                                    </svg>
                                </a>

                                {{-- Botón Eliminar --}}
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-400">No hay actividades registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                    {{-- Links de paginación --}}
        <div class="mt-4">
            {{ $tasks->links() }}
        </div>

        </div>
    </div>
    <script>
    function abrirModalEditar(id, title, description, responsible, dueDate, status) {
        // Llena los campos con la info de la tarea
        document.getElementById('editTitle').value       = title;
        document.getElementById('editDescription').value = description;
        document.getElementById('editResponsible').value = responsible;
        document.getElementById('editDueDate').value     = dueDate;
        document.getElementById('editStatus').value      = status;

        // Cambia el action del form con el ID de la tarea
        document.getElementById('formEditar').action = `/tasks/${id}`;

        // Abre el modal
        document.getElementById('modalEditar').classList.remove('hidden');
    }
</script>
@endsection
