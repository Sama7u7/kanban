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

            <div id="modalCrear" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-full max-w-lg">

                    {{-- Header del modal con botón X --}}
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-400">Nueva actividad</h2>
                        <button onclick="document.getElementById('modalCrear').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                            &times;
                        </button>
                    </div>

                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Título</label>
                            <input  type="text" name="title" required
                                class="text-gray-400 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" rows="3"
                                    class="text-gray-400 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Responsable</label>
                            <input type="text" name="responsible"
                                class="text-gray-400 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Fecha límite</label>
                            <input type="date" name="due_date" required
                                class="text-gray-400 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Estatus</label>
                            <select name="status"
                                    class="text-gray-400 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="por_hacer">🟡 Por hacer</option>
                                    <option value="haciendo">🔵 Haciendo</option>
                                    <option value="hecho">🟢 Hecho</option>
                                    <option value="cancelado">🔴 Cancelado</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button"
                                    onclick="document.getElementById('modalCrear').classList.add('hidden')"
                                    class="border px-4 py-2 rounded hover:bg-gray-100">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-indigo-600 text-white font-bold px-4 py-2 rounded hover:bg-indigo-700">
                                Guardar
                            </button>
                        </div>

                    </form>

                </div>
            </div>
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
                                {{-- Ventana modal boton editar --}}
                                <div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-xl p-6 w-full max-w-lg">

                                        {{-- Header del modal con botón X --}}
                                        <div class="flex justify-between items-center mb-4">
                                            <h2 class="text-xl font-bold text-gray-400">Editar actividad</h2>
                                            <button onclick="document.getElementById('modalEditar').classList.add('hidden')"
                                                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                                                &times;
                                            </button>
                                        </div>

                                        <form method="POST" id="formEditar" action="">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Título</label>
                                                <input type="text" name="title" id="editTitle" required
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                                <textarea name="description" id="editDescription" rows="3"
                                                        class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Responsable</label>
                                                <input type="text" name="responsible" id="editResponsible"
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Fecha límite</label>
                                                <input type="date" name="due_date" id="editDueDate" required
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Estatus</label>
                                                <select name="status" id="editStatus"
                                                        class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="por_hacer">🟡 Por hacer</option>
                                                    <option value="haciendo">🔵 Haciendo</option>
                                                    <option value="hecho">🟢 Hecho</option>
                                                    <option value="cancelado">🔴 Cancelado</option>
                                                </select>
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                        onclick="document.getElementById('modalEditar').classList.add('hidden')"
                                                        class="border px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                        class="bg-indigo-600 text-white font-bold px-4 py-2 rounded hover:bg-indigo-700">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

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
