@extends('layouts.test')

@section('content')
    {{-- Contenedor principal con el estado de Alpine.js --}}
    <div x-data="{
        showCreateModal: false,
        showEditModal: false,
        showViewModal: false,
        selectedTask: { id: null, title: '', description: '', responsible: '', requester: '', due_date: '', status: '', responsible_user: {}, requester_user: {} }
    }" x-cloak>

        <h1 class="text-3xl md:text-4xl text-white font-mono pb-8">Tareas</h1>

        <div class="border rounded-xl border-gray-700 bg-[#161615]/50 px-4 py-6 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                <h2 class="text-xl text-gray-400 font-semibold text-center md:text-left">Gestión de Actividades</h2>

                <button @click="showCreateModal = true"
                    class="w-full md:w-auto bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                    + Crear una actividad
                </button>
            </div>

            <div class="overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <table class="w-full text-white border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-gray-500 text-sm uppercase tracking-wider">
                            <th class="px-4 py-2 text-left">Actividad</th>
                            <th class="px-4 py-2">Estatus</th>
                            <th class="px-4 py-2 hidden md:table-cell">Responsable</th>
                            <th class="px-4 py-2 hidden lg:table-cell">Solicitante</th>
                            <th class="px-4 py-2 text-center">Fecha límite</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm md:text-base">
                        @forelse ($tasks as $task)
                            <tr class="bg-[#1b1b18] hover:bg-[#252522] transition-colors rounded-lg">
                                <td class="px-4 py-4 rounded-l-lg font-medium">{{ $task->title }}</td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $badges = [
                                            'por_hacer' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                            'haciendo' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                            'hecho' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                            'cancelado' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-widest {{ $badges[$task->status] ?? 'bg-gray-500/10 text-gray-500 border-gray-500/20' }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-center hidden md:table-cell text-gray-300">
                                    {{ $task->responsibleUser->name ?? 'Sin asignar' }}
                                </td>

                                <td class="px-4 py-4 text-center hidden lg:table-cell text-gray-300">
                                    {{ $task->requesterUser->name ?? 'Sin asignar' }}
                                </td>

                                <td class="px-4 py-4 text-center font-mono text-gray-400">
                                    {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'N/A' }}
                                </td>

                                <td class="px-4 py-4 rounded-r-lg">
                                    <div class="flex items-center justify-center gap-4">
                                        {{-- Ver Info --}}
                                        <button @click="selectedTask = @js($task); showViewModal = true"
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>

                                        {{-- Editar --}}
                                        <button
                                            @click="selectedTask = { 
                                            id: {{ $task->id }}, 
                                            title: @js($task->title), 
                                            description: @js($task->description), 
                                            responsible: @js($task->responsible), 
                                            requester: @js($task->requester), 
                                            due_date: '{{ $task->due_date?->format('Y-m-d') }}', 
                                            status: @js($task->status) 
                                        }; showEditModal = true"
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                                            </svg>
                                        </button>

                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                            onsubmit="return confirm('¿Eliminar actividad?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-400 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-gray-500">No hay actividades registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $tasks->links() }}
            </div>
        </div>

        {{-- ==========================================
             MODAL: CREAR TAREA
             ========================================== --}}
        <x-modal-base id="modalCrear" title="Nueva Actividad" showVariable="showCreateModal">
            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Título</label>
                    <input type="text" name="title" required
                        class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Descripción</label>
                    <textarea name="description" rows="2"
                        class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Responsable
                            (IT)</label>
                        <select required name="responsible"
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                            <option value="" disabled selected>Seleccionar...</option>
                            @foreach ($itUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Solicitante</label>
                        <select required name="requester"
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                            <option value="" disabled selected>Seleccionar...</option>
                            @foreach ($nonItUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Fecha
                            límite</label>
                        <input type="date" name="due_date" required
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Estatus
                            inicial</label>
                        <select name="status"
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                            <option value="por_hacer">🟡 Por hacer</option>
                            <option value="haciendo">🔵 Haciendo</option>
                            <option value="hecho">🟢 Hecho</option>
                            <option value="cancelado">🔴 Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                    <button type="button" @click="showCreateModal = false"
                        class="text-gray-500 text-xs font-mono uppercase tracking-widest px-4 py-2">Cancelar</button>
                    <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-indigo-700 flex items-center">
                        <span x-show="!loading">Guardar Actividad</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </div>
            </form>
        </x-modal-base>

        {{-- ==========================================
             MODAL: EDITAR TAREA
             ========================================== --}}
        <x-modal-base id="modalEditar" title="Editar Actividad" showVariable="showEditModal">
            <form method="POST" :action="'/tasks/' + selectedTask.id" class="space-y-4" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf @method('PUT')
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Título</label>
                    <input type="text" name="title" x-model="selectedTask.title" required
                        class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Descripción</label>
                    <textarea name="description" x-model="selectedTask.description" rows="2"
                        class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Estatus</label>
                        <select name="status" x-model="selectedTask.status"
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                            <option value="por_hacer">Por hacer</option>
                            <option value="haciendo">Haciendo</option>
                            <option value="hecho">Hecho</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 font-mono">Fecha
                            límite</label>
                        <input type="date" name="due_date" x-model="selectedTask.due_date"
                            class="mt-1 w-full border-gray-700 bg-gray-800 text-white rounded-lg px-3 py-2">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="text-gray-500 text-xs font-mono uppercase tracking-widest px-4 py-2">Cancelar</button>
                    <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-indigo-700">
                        <span x-show="!loading">Actualizar</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </div>
            </form>
        </x-modal-base>

        {{-- ==========================================
             MODAL: VER DETALLES (TEXTOS EN BLANCO)
             ========================================== --}}
        <x-modal-base id="modalVer" title="Detalles de la Actividad" showVariable="showViewModal">
            <div class="space-y-4 py-2">

                {{-- Título --}}
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Título</label>
                    <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2">
                        <p class="text-white text-base font-semibold" x-text="selectedTask.title"></p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="space-y-1">
                    <label
                        class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Descripción</label>
                    <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2 min-h-[60px]">
                        <p class="text-white text-sm leading-relaxed"
                            x-text="selectedTask.description || 'Sin descripción detallada.'"></p>
                    </div>
                </div>

                {{-- Responsable y Solicitante: Ahora en Blanco Puro --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label
                            class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Responsable</label>
                        <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2">
                            <p class="text-white text-sm font-medium"
                                x-text="selectedTask.responsible_user?.name || 'No asignado'"></p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label
                            class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Solicitante</label>
                        <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2">
                            <p class="text-white text-sm font-medium"
                                x-text="selectedTask.requester_user?.name || 'No asignado'"></p>
                        </div>
                    </div>
                </div>

                {{-- Fecha Límite --}}
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Fecha
                        Límite</label>
                    <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2">
                        <p class="text-white text-sm font-mono"
                            x-text="selectedTask.due_date ? selectedTask.due_date.substring(0, 10).split('-').reverse().join('/') : 'N/A'">
                        </p>
                    </div>
                </div>

                {{-- Estatus --}}
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Estatus</label>
                    <div class="flex items-center pt-1">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-widest"
                            :class="{
                                'bg-yellow-500/10 text-yellow-500 border-yellow-500/20': selectedTask
                                    .status === 'por_hacer',
                                'bg-blue-500/10 text-blue-500 border-blue-500/20': selectedTask.status === 'haciendo',
                                'bg-green-500/10 text-green-500 border-green-500/20': selectedTask
                                    .status === 'hecho',
                                'bg-red-500/10 text-red-500 border-red-500/20': selectedTask.status === 'cancelado'
                            }"
                            x-text="selectedTask.status ? selectedTask.status.replace('_', ' ') : ''">
                        </span>
                    </div>
                </div>

                {{-- Botón Cerrar --}}
                <div class="flex justify-end pt-4">
                    <button type="button" @click="showViewModal = false"
                        class="bg-gray-800 hover:bg-gray-700 text-white text-[10px] uppercase tracking-widest font-mono px-8 py-2.5 rounded-lg border border-gray-700 transition-all">
                        Cerrar
                    </button>
                </div>
            </div>
        </x-modal-base>

    </div> {{-- FIN X-DATA --}}

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
