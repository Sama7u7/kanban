@extends('layouts.test')

@section('content')
    @php
        $kanbanColumns = [
            ['id' => 'por_hacer', 'name' => 'Por Hacer', 'color' => 'yellow', 'dot' => 'bg-yellow-500'],
            ['id' => 'haciendo', 'name' => 'Haciendo', 'color' => 'blue', 'dot' => 'bg-blue-500'],
            ['id' => 'hecho', 'name' => 'Hecho', 'color' => 'green', 'dot' => 'bg-green-500'],
            ['id' => 'cancelado', 'name' => 'Cancelado', 'color' => 'red', 'dot' => 'bg-red-500'],
        ];
        // Definimos si es admin para mostrar el botón eliminar
        $isAdmin = auth()->user()->hasPermission('view_all_tasks');
        $isWorkerOrAdmin = $isAdmin || auth()->user()->hasPermission('atender_tareas');
    @endphp

    <div x-data="taskManager()" x-cloak class="flex flex-col w-full pb-10">

        <h1 class="text-3xl md:text-4xl text-white font-mono pb-8 shrink-0">Tareas</h1>

        <div
            class="border rounded-xl border-gray-700 bg-[#161615]/50 px-4 py-6 md:px-6 flex-1 flex flex-col overflow-hidden">

            {{-- CABECERA --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 shrink-0">
                <h2 class="text-xl text-gray-400 font-semibold">Gestión de Actividades</h2>

                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                    <div class="bg-gray-800 p-1 rounded-lg flex items-center shrink-0">
                        <button type="button" @click="setView('table')"
                            :class="viewMode === 'table' ? 'bg-[#1b1b18] text-white shadow' : 'text-gray-400'"
                            class="px-4 py-1.5 text-xs font-bold uppercase tracking-widest rounded-md transition-all">Tabla</button>
                        <button type="button" @click="setView('kanban')"
                            :class="viewMode === 'kanban' ? 'bg-[#1b1b18] text-white shadow' : 'text-gray-400'"
                            class="px-4 py-1.5 text-xs font-bold uppercase tracking-widest rounded-md transition-all">Kanban</button>
                    </div>

                    <button type="button" @click="showCreateModal = true"
                        class="bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-all shadow-lg shrink-0 p-3 md:py-2 md:px-6 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden md:inline">Crear actividad</span>
                    </button>
                </div>
            </div>

            {{-- FILTROS PC --}}
            <div class="hidden lg:block mb-8 bg-[#1b1b18]/40 border border-gray-700/50 p-4 rounded-xl shrink-0 w-full">
                <div class="flex flex-row items-end gap-4 w-full">
                    <div class="flex-1">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 font-mono tracking-widest">Buscar
                            actividad</label>
                        <input type="text" x-model="filters.search" placeholder="Escribe para filtrar..."
                            class="w-full bg-[#1b1b18] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                    <div class="w-48 shrink-0">
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 font-mono tracking-widest">Estatus</label>
                        <select x-model="filters.status"
                            class="w-full bg-[#1b1b18] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white outline-none cursor-pointer">
                            <option value="">Todos</option>
                            @foreach ($kanbanColumns as $col)
                                <option value="{{ $col['id'] }}">{{ $col['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($isAdmin)
                        <div class="w-64 shrink-0">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase mb-1 ml-1 font-mono tracking-widest">Responsable</label>
                            <select x-model="filters.responsible"
                                class="w-full bg-[#1b1b18] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white outline-none cursor-pointer">
                                <option value="">Cualquiera</option>
                                @foreach ($itUsers as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="shrink-0" x-show="filters.search || filters.status || filters.responsible">
                        <button @click="resetFilters()"
                            class="h-[38px] w-[38px] flex items-center justify-center bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-lg transition-all border border-red-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- FILTROS MÓVIL --}}
            <div class="lg:hidden mb-8 bg-[#1b1b18]/40 border border-gray-700/50 p-4 rounded-xl shrink-0 space-y-4">
                <input type="text" x-model="filters.search" placeholder="Buscar..."
                    class="w-full bg-[#1b1b18] border border-gray-700 rounded-lg px-4 py-2 text-sm text-white focus:ring-1 focus:ring-indigo-500 outline-none">
                <div class="flex gap-4">
                    <select x-model="filters.status"
                        class="flex-1 bg-[#1b1b18] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white outline-none">
                        <option value="">Todos los estados</option>
                        @foreach ($kanbanColumns as $col)
                            <option value="{{ $col['id'] }}">{{ $col['name'] }}</option>
                        @endforeach
                    </select>
                    <button @click="resetFilters()" x-show="filters.search || filters.status || filters.responsible"
                        class="h-[38px] w-[38px] flex items-center justify-center bg-red-500/10 text-red-500 rounded-lg border border-red-500/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- CONTENIDO --}}
            <div class="flex-1 min-h-0 flex flex-col">
                {{-- VISTA TABLA: Estilo Jira compacto --}}
                <div x-show="viewMode === 'table'" class="flex-1 overflow-y-auto pr-1 custom-scrollbar">
                    {{-- PC --}}
                    <div class="hidden lg:block">
                        <table class="w-full text-white border-collapse">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <th
                                        class="px-3 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest w-16">
                                        ID</th>
                                    <th
                                        class="px-3 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Actividad</th>
                                    <th
                                        class="px-3 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest w-28">
                                        Estatus</th>
                                    <th
                                        class="px-3 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest w-36">
                                        Responsable</th>
                                    <th
                                        class="px-3 py-2 text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest w-20">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/60">
                                <template x-for="task in filteredTasks" :key="task.id">
                                    <tr class="hover:bg-[#1b1b18] transition-colors duration-100 group">
                                        {{-- Task ID --}}
                                        <td class="px-3 py-2">
                                            <span class="text-[11px] font-mono text-gray-600" x-text="'#' + task.id"></span>
                                        </td>
                                        {{-- Task tittle --}}
                                        <td class="px-3 py-2">
                                            <span
                                                class="text-sm text-gray-200 group-hover:text-white transition-colors font-medium"
                                                :class="task.status === 'hecho' || task.status === 'cancelado' ?
                                                    'line-through text-gray-500' : ''"
                                                x-text="task.title"></span>
                                        </td>
                                        {{-- Task Estatus --}}
                                        <td class="px-3 py-2">
                                            <div class="relative" x-data="{ open: false }">
                                                {{-- Badge / Botón - Agregamos x-ref --}}
                                                <button x-ref="statusButton" @click.stop="open = !open"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border cursor-pointer hover:opacity-80 transition-opacity"
                                                    :class="getStatusClasses(task.status)">
                                                    <span x-text="task.status.replace('_', ' ')"></span>
                                                    <svg class="w-2.5 h-2.5 opacity-60" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>

                                                {{-- Dropdown con Teleport y Anchor --}}
                                                {{-- Dropdown con Teleport y Anchor --}}
                                                <template x-teleport="body">
                                                    <div x-show="open" @click.outside="open = false"
                                                        {{-- Forzamos el anclaje --}}
                                                        x-anchor.bottom-start.offset.4="$refs.statusButton" x-transition
                                                        {{-- Importante: 'fixed' o 'absolute' y un z-index muy alto --}}
                                                        class="fixed z-[9999] bg-[#1b1b18] border border-gray-700 rounded-lg shadow-2xl py-1 min-w-[140px]"
                                                        style="display: none;">
                                                        @foreach ($kanbanColumns as $col)
                                                            @php $allowedForUser = in_array($col['id'], ['por_hacer', 'cancelado']); @endphp
                                                            @if ($isWorkerOrAdmin || $allowedForUser)
                                                                <button type="button"
                                                                    @click.stop="quickStatus(task, '{{ $col['id'] }}'); open = false"
                                                                    {{-- 
                                                                       Cambiamos dinámicamente las clases:
                                                                       - Si es el actual: bg-white/10 y texto blanco
                                                                       - Si no: texto gris y hover con fondo suave
                                                                    --}}
                                                                    class="w-full text-left px-3 py-2 text-[11px] font-semibold flex items-center gap-2 transition-colors"
                                                                    :class="task.status === '{{ $col['id'] }}' ?
                                                                        'bg-white/10 text-white' :
                                                                        'text-gray-400 hover:bg-white/5 hover:text-white'">
                                                                    <span
                                                                        class="w-2 h-2 rounded-full {{ $col['dot'] }} shrink-0"></span>

                                                                    <span class="flex-1">{{ $col['name'] }}</span>

                                                                    {{-- Añadimos un pequeño check si es el seleccionado --}}
                                                                    <template
                                                                        x-if="task.status === '{{ $col['id'] }}'">
                                                                        <svg class="w-3.5 h-3.5 text-blue-400"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="3"
                                                                                d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    </template>
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                        {{-- Task responsible --}}
                                        <td class="px-3 py-2">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-5 h-5 rounded-full bg-indigo-600/80 flex items-center justify-center text-white text-[9px] font-bold shrink-0"
                                                    x-text="task.responsible_user?.name ? task.responsible_user.name.substring(0,2).toUpperCase() : '?'">
                                                </div>
                                                <span class="text-xs text-gray-400 truncate max-w-[100px]"
                                                    x-text="task.responsible_user?.name || 'Sin asignar'"></span>
                                            </div>
                                        </td>
                                        {{-- Task actions --}}
                                        <td class="px-3 py-2 text-center">
                                            <div
                                                class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="openViewFromKanban(task)"
                                                    class="p-1 text-gray-500 hover:text-indigo-400 transition-colors"
                                                    title="Ver">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                <button @click="openEditFromKanban(task)"
                                                    class="p-1 text-gray-500 hover:text-indigo-400 transition-colors"
                                                    title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                @if ($isAdmin)
                                                    <form method="POST" :action="`/tasks/${task.id}`"
                                                        onsubmit="return confirm('¿Eliminar actividad?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="p-1 text-gray-500 hover:text-red-400 transition-colors"
                                                            title="Eliminar">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- CARDS MÓVIL: solo nombre, responsable y estatus --}}
                    <div class="lg:hidden divide-y divide-gray-800/60">
                        <template x-for="task in filteredTasks" :key="task.id">
                            <div class="flex items-center justify-between py-3 px-1 gap-3">
                                <span class="text-sm text-gray-200 font-medium flex-1 truncate"
                                    x-text="task.title"></span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <div class="flex items-center gap-1">
                                        <div class="w-5 h-5 rounded-full bg-indigo-600/80 flex items-center justify-center text-white text-[9px] font-bold"
                                            x-text="task.responsible_user?.name ? task.responsible_user.name.substring(0,1).toUpperCase() : '?'">
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border"
                                        :class="getStatusClasses(task.status)"
                                        x-text="task.status.replace('_', ' ')"></span>
                                    <button @click="openViewFromKanban(task)"
                                        class="text-gray-500 hover:text-indigo-400 p-1" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button @click="openEditFromKanban(task)"
                                        class="text-gray-500 hover:text-indigo-400 p-1" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- VISTA KANBAN: BOTONES SIEMPRE VISIBLES --}}
                <div x-show="viewMode === 'kanban'" class="flex-1 overflow-x-auto pb-4 custom-scrollbar w-full">
                    <div class="flex gap-4 lg:gap-6 min-w-max lg:min-w-0 w-full h-full">
                        @foreach ($kanbanColumns as $column)
                            @php $canDropHere = $isWorkerOrAdmin || in_array($column['id'], ['por_hacer', 'cancelado']); @endphp
                            <div class="flex flex-col flex-1 min-w-[280px] lg:min-w-0 h-full">
                                <div class="flex items-center justify-between mb-4 px-2 shrink-0">
                                    <h3
                                        class="text-sm font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $column['dot'] }}"></span>
                                        {{ $column['name'] }}
                                    </h3>
                                    <span
                                        class="text-xs text-gray-500 bg-gray-800/50 px-2.5 py-0.5 rounded-full border border-gray-700"
                                        x-text="getTasksByStatus('{{ $column['id'] }}').length"></span>
                                </div>

                                <div class="bg-[#161615] border border-gray-800 rounded-xl p-4 space-y-4 flex-1 overflow-y-auto custom-scrollbar shadow-inner"
                                    @if ($canDropHere) @dragover.prevent="dragOverContext = '{{ $column['id'] }}'" 
                        @dragleave.prevent="dragOverContext = null" 
                        @drop.prevent="dropTask('{{ $column['id'] }}')" @endif
                                    :class="dragOverContext === '{{ $column['id'] }}' ?
                                        'ring-2 ring-{{ $column['color'] }}-500/50 bg-{{ $column['color'] }}-500/5' :
                                        ''">

                                    {{-- TARJETA KANBAN CORREGIDA CON BOTONES EN LA ZONA MARCADA --}}
                                    <template x-for="task in getTasksByStatus('{{ $column['id'] }}')"
                                        :key="task.id">
                                        <div :draggable="{{ $isWorkerOrAdmin ? 'true' : '(task.status === \'por_hacer\' || task.status === \'cancelado\')' }}"
                                            @dragstart="dragStart($event, task)" @dragend="dragEnd()"
                                            class="bg-[#1b1b18] border border-gray-700 p-4 rounded-lg flex flex-col gap-3 shadow-sm cursor-grab active:cursor-grabbing hover:border-gray-500 transition-all duration-200 group">

                                            {{-- FILA SUPERIOR: ID + Botones de acción --}}
                                            <div class="flex items-center justify-between">
                                                <span class="text-[10px] font-mono text-gray-600"
                                                    x-text="'#' + task.id"></span>
                                                <div class="flex items-center gap-1.5">
                                                    {{-- Editar --}}
                                                    <button @click.stop="openEditFromKanban(task)"
                                                        class="p-1.5 bg-indigo-600/20 text-indigo-400 rounded-md hover:bg-indigo-600 hover:text-white transition-colors"
                                                        title="Editar">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2.5"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>

                                                    {{-- Eliminar (Solo Admin) --}}
                                                    @if ($isAdmin)
                                                        <form method="POST" :action="`/tasks/${task.id}`"
                                                            onsubmit="return confirm('¿Eliminar actividad?')" @click.stop>
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="p-1.5 bg-red-600/20 text-red-400 rounded-md hover:bg-red-600 hover:text-white transition-colors"
                                                                title="Eliminar">
                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- CONTENIDO: Título y Descripción --}}
                                            <div @click="openViewFromKanban(task)" class="cursor-pointer space-y-1.5">
                                                <h4 class="text-white text-sm font-bold uppercase tracking-wide leading-snug"
                                                    :class="task.status === 'hecho' || task.status === 'cancelado' ?
                                                        'line-through text-gray-500' : ''"
                                                    x-text="task.title"></h4>
                                                <p class="text-gray-400 text-xs line-clamp-2 leading-relaxed"
                                                    x-text="task.description || 'Sin descripción'"></p>
                                            </div>

                                            {{-- FOOTER: Responsable y Fecha --}}
                                            <div
                                                class="flex justify-between items-center border-t border-gray-800 pt-3 mt-1">
                                                <div @click="openViewFromKanban(task)"
                                                    class="flex items-center gap-2 cursor-pointer">
                                                    <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center text-white text-[10px] font-bold"
                                                        x-text="task.responsible_user?.name ? task.responsible_user.name.substring(0,2).toUpperCase() : '??'">
                                                    </div>
                                                    <span class="text-[10px] text-gray-300 font-medium"
                                                        x-text="task.responsible_user?.name ? task.responsible_user.name.split(' ')[0] : 'Sin asignar'"></span>
                                                </div>
                                                <div
                                                    class="bg-gray-900/50 border border-gray-800 px-2 py-0.5 rounded shadow-inner">
                                                    <span class="text-[10px] text-gray-400 font-mono"
                                                        x-text="task.due_date ? task.due_date.substring(0, 10).split('-').reverse().join('/') : 'N/A'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- MODALES --}}
                <x-modal-base id="modalCrear" title="Nueva Actividad" showVariable="showCreateModal">
                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4" x-data="{ loading: false }"
                        @submit="loading = true">
                        @csrf
                        <div><label
                                class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Título</label><input
                                type="text" name="title" required
                                class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div><label
                                class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Descripción</label>
                            <textarea name="description" rows="2"
                                class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            @if ($isWorkerOrAdmin)
                                <div><label
                                        class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Responsable</label>
                                    <select name="responsible"
                                        class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none">
                                        <option value="">Sin asignar</option>
                                        @foreach ($itUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div><label class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Fecha
                                    límite</label><input type="date" name="due_date" required
                                    class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                            <button type="button" @click="showCreateModal = false"
                                class="text-gray-500 text-xs font-mono px-4 py-2 hover:text-white uppercase">Cancelar</button>
                            <button type="submit" :disabled="loading"
                                class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg">Guardar</button>
                        </div>
                    </form>
                </x-modal-base>

                <x-modal-base id="modalEditar" title="Editar Actividad" showVariable="showEditModal">
                    <form method="POST" :action="'/tasks/' + selectedTask.id" class="space-y-4" x-data="{ loading: false }"
                        @submit="loading = true">
                        @csrf @method('PUT')

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Título</label>
                            <input type="text" name="title" x-model="selectedTask.title" required
                                class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Estatus</label>
                                <select name="status" x-model="selectedTask.status"
                                    class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none">

                                    @if ($isWorkerOrAdmin)
                                        {{-- El Admin/IT ve todo siempre --}}
                                        <template x-for="col in @js($kanbanColumns)" :key="col.id">
                                            <option :value="col.id" x-text="col.name"></option>
                                        </template>
                                    @else
                                        {{-- El Usuario común tiene restricciones --}}
                                        <option value="por_hacer">🟡 Por Hacer (Reactivar)</option>
                                        <option value="cancelado">🔴 Cancelado</option>

                                        {{-- Si la tarea ya está en 'haciendo' o 'hecho', mostramos la opción pero deshabilitada 
                             para que no la puedan cambiar a esos estados si no están ahí, 
                             o que se vea el estado actual pero no puedan volver a ponerlo si lo cambian --}}
                                        <option value="haciendo" x-show="selectedTask.status === 'haciendo'" disabled>🔵
                                            Haciendo (Solo lectura)</option>
                                        <option value="hecho" x-show="selectedTask.status === 'hecho'" disabled>🟢 Hecho
                                            (Solo lectura)</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-gray-500 font-mono">Fecha
                                    límite</label>
                                <input type="date" name="due_date" x-model="selectedTask.due_date"
                                    class="mt-1 w-full border-gray-700 bg-[#1b1b18] text-white rounded-lg px-3 py-2 outline-none">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                            <button type="button" @click="showEditModal = false"
                                class="text-gray-500 text-xs font-mono px-4 py-2 hover:text-white uppercase">Cancelar</button>
                            <button type="submit" :disabled="loading"
                                class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </x-modal-base>

                <x-modal-base id="modalVer" title="Detalles" showVariable="showViewModal">
                    <div class="space-y-4 py-2">
                        <div class="space-y-1"><label
                                class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Título</label>
                            <div class="border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2 text-white font-semibold"
                                x-text="selectedTask.title"></div>
                        </div>
                        <div class="space-y-1"><label
                                class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Descripción</label>
                            <div class="border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2 text-white text-sm"
                                x-text="selectedTask.description || 'Sin descripción'"></div>
                        </div>
                        <div class="flex justify-end pt-4"><button type="button" @click="showViewModal = false"
                                class="bg-gray-800 text-white text-[10px] font-mono px-8 py-2 rounded-lg border border-gray-700">Cerrar</button>
                        </div>
                    </div>
                </x-modal-base>

            </div>

            <script>
                function taskManager() {
                    return {
                        viewMode: localStorage.getItem('taskViewMode') || 'table',
                        showCreateModal: false,
                        showEditModal: false,
                        showViewModal: false,
                        filters: {
                            search: '',
                            status: '',
                            responsible: ''
                        },
                        selectedTask: {
                            id: null,
                            title: '',
                            description: '',
                            status: '',
                            due_date: ''
                        },
                        tasksList: @js($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator ? $tasks->items() : $tasks),
                        draggedTask: null,
                        dragOverContext: null,
                        get filteredTasks() {
                            return this.tasksList.filter(task => {
                                const s = this.filters.search.toLowerCase();
                                return (task.title.toLowerCase().includes(s) || (task.description && task.description
                                        .toLowerCase().includes(s))) &&
                                    (this.filters.status === '' || task.status === this.filters.status) &&
                                    (this.filters.responsible === '' || (task.responsible_user && task.responsible_user
                                        .name === this.filters.responsible));
                            });
                        },
                        getStatusClasses(status) {
                            return {
                                'por_hacer': 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                'haciendo': 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                'hecho': 'bg-green-500/10 text-green-500 border-green-500/20',
                                'cancelado': 'bg-red-500/10 text-red-500 border-red-500/20'
                            } [status];
                        },
                        getTasksByStatus(status) {
                            return this.filteredTasks.filter(t => t.status === status);
                        },
                        resetFilters() {
                            this.filters.search = '';
                            this.filters.status = '';
                            this.filters.responsible = '';
                        },
                        setView(mode) {
                            this.viewMode = mode;
                            localStorage.setItem('taskViewMode', mode);
                        },
                        dragStart(event, task) {
                            this.draggedTask = task;
                            setTimeout(() => event.target.classList.add('opacity-50'), 0);
                        },
                        dragEnd() {
                            document.querySelectorAll('.opacity-50').forEach(c => c.classList.remove('opacity-50'));
                            this.dragOverContext = null;
                        },
                        dropTask(newStatus) {
                            if (this.draggedTask && this.draggedTask.status !== newStatus) {
                                const index = this.tasksList.findIndex(t => t.id === this.draggedTask.id);
                                this.tasksList[index].status = newStatus;
                                fetch(`/tasks/${this.draggedTask.id}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                });
                            }
                        },
                        openEditFromKanban(task) {
                            this.selectedTask = {
                                ...task,
                                due_date: task.due_date ? task.due_date.substring(0, 10) : ''
                            };
                            this.showEditModal = true;
                        },
                        openViewFromKanban(task) {
                            this.selectedTask = task;
                            this.showViewModal = true;
                        },
                        async quickStatus(task, newStatus) {
                            if (task.status === newStatus) return;
                            const index = this.tasksList.findIndex(t => t.id === task.id);
                            if (index === -1) return;
                            const original = task.status;
                            this.tasksList[index].status = newStatus;
                            try {
                                const resp = await fetch(`/tasks/${task.id}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        status: newStatus
                                    })
                                });
                                if (!resp.ok) throw new Error();
                            } catch (e) {
                                this.tasksList[index].status = original;
                            }
                        }
                    }
                }
            </script>
            <style>
                [x-cloak] {
                    display: none !important;
                }

                .custom-scrollbar::-webkit-scrollbar {
                    width: 4px;
                    height: 4px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #374151;
                    border-radius: 10px;
                }
            </style>
        </div>
    @endsection
