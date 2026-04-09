@extends('layouts.test')

@section('content')
    @php
        // Variables auxiliares para limpiar la lógica en la vista
        $isWorkerOrAdmin =
            auth()->user()->hasPermission('atender_tareas') || auth()->user()->hasPermission('view_all_tasks');
        $showUsersCard = auth()->user()->hasPermission('manage_users') || $isWorkerOrAdmin;
    @endphp

    <div class="h-full flex flex-col gap-6 overflow-y-auto pr-2 custom-scrollbar">

        {{-- CABECERA --}}
        <div class="shrink-0">
            <h1 class="text-3xl md:text-4xl text-white font-mono">Panel de Control</h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ auth()->user()->hasPermission('view_all_tasks') ? 'Resumen general de la operación de Taskify.' : 'Tus actividades y solicitudes.' }}
            </p>
        </div>

        {{-- 1. TARJETAS DE MÉTRICAS (KPIs) --}}
        {{-- Aquí aplicamos magia de Tailwind: 4 columnas si muestra usuarios, 3 columnas si los oculta --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 {{ $showUsersCard ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4 shrink-0">

            {{-- Total Tareas --}}
            <div
                class="bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-5 flex items-center gap-4 shadow-lg">
                <div
                    class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0 border border-indigo-500/30 shadow-[0_0_15px_-3px_rgba(79,70,229,0.5)]">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-100 text-[10px] uppercase tracking-widest font-bold">
                        @if (auth()->user()->hasPermission('view_all_tasks'))
                            Total Sistema
                        @elseif(auth()->user()->hasPermission('atender_tareas'))
                            Mis Tareas
                        @else
                            Mis Solicitudes
                        @endif
                    </p>
                    <p class="text-2xl text-white font-mono leading-tight">{{ $totalTasks }}</p>
                </div>
            </div>

            {{-- Completadas --}}
            <div
                class="bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-5 flex items-center gap-4 shadow-lg">
                <div
                    class="w-12 h-12 rounded-lg bg-green-500/20 flex items-center justify-center text-green-400 shrink-0 border border-green-500/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-100 text-[10px] uppercase tracking-widest font-bold">Finalizadas</p>
                    <p class="text-2xl text-white font-mono leading-tight">{{ $completedTasks }}</p>
                </div>
            </div>

            {{-- Vencidas --}}
            <div
                class="bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-5 flex items-center gap-4 shadow-lg relative overflow-hidden">
                @if ($overdueTasks > 0)
                    <div class="absolute top-0 right-0 w-16 h-16 bg-red-500/10 rounded-bl-full -mr-8 -mt-8 animate-pulse">
                    </div>
                @endif
                <div
                    class="w-12 h-12 rounded-lg bg-red-500/20 flex items-center justify-center text-red-400 shrink-0 border border-red-500/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-100 text-[10px] uppercase tracking-widest font-bold">Vencidas</p>
                    <p class="text-2xl font-mono leading-tight {{ $overdueTasks > 0 ? 'text-red-400' : 'text-white' }}">
                        {{ $overdueTasks }}
                    </p>
                </div>
            </div>

            {{-- Usuarios (SOLO VISIBLE SI TIENE PERMISOS) --}}
            @if ($showUsersCard)
                <div
                    class="bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-5 flex items-center gap-4 shadow-lg">
                    <div
                        class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 shrink-0 border border-blue-500/30">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-100 text-[10px] uppercase tracking-widest font-bold">
                            {{ auth()->user()->hasPermission('manage_users') ? 'Usuarios App' : 'Equipo' }}
                        </p>
                        <p class="text-2xl text-white font-mono leading-tight">{{ $activeUsers }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. GRÁFICO Y LISTA DIVIDIDA --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-[500px] mb-6">

            {{-- Gráfico de Dona --}}
            <div
                class="bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-6 flex flex-col shadow-lg h-fit">
                {{-- Título dinámico del gráfico --}}
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-widest mb-8 text-center lg:text-left">
                    {{ auth()->user()->hasPermission('view_all_tasks') ? 'Estado del Proyecto' : 'Estado de mis Tareas' }}
                </h3>
                <div class="relative w-full h-72 flex items-center justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Sección de Atención Requerida --}}
            <div
                class="lg:col-span-2 bg-gray-800/40 border border-gray-700/50 backdrop-blur-md rounded-xl p-6 flex flex-col shadow-lg">
                <div class="flex justify-between items-center mb-8 border-b border-gray-700/50 pb-4">
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Atención Requerida
                    </h3>
                    <a href="{{ route('tasks.index') }}"
                        class="text-[10px] text-indigo-400 hover:text-indigo-300 font-bold uppercase tracking-tighter transition-colors">
                        Ver todas las tareas &rarr;
                    </a>
                </div>

                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar space-y-12">

                    {{-- GRUPO 1: TAREAS DONDE SOY RESPONSABLE (SOLO SI TIENE PERMISO DE ATENDER) --}}
                    @if ($isWorkerOrAdmin)
                        <div>
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="p-2.5 bg-indigo-500/20 rounded-lg border border-indigo-500/30 shadow-[0_0_15px_-3px_rgba(79,70,229,0.5)]">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-white font-bold uppercase tracking-widest">Mis Pendientes</span>
                                <div class="h-[1px] bg-indigo-500/30 flex-1"></div>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                @forelse ($urgentTasks->where('responsible', auth()->id()) as $task)
                                    <div
                                        class="bg-gray-900/40 border border-gray-700/50 rounded-lg p-3 hover:bg-gray-900/60 transition-all">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-white text-sm font-medium truncate">{{ $task->title }}
                                                </h4>
                                                <p class="text-[10px] text-gray-400 uppercase mt-1 tracking-tighter">
                                                    Solicitado por: <span
                                                        class="text-indigo-400/80">{{ $task->requesterUser->name }}</span>
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3 shrink-0">
                                                <span
                                                    class="text-[8px] px-2 py-0.5 rounded bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 font-bold uppercase">{{ str_replace('_', ' ', $task->status) }}</span>
                                                <p
                                                    class="text-[11px] font-mono {{ $task->due_date < now() ? 'text-red-400' : 'text-gray-400' }}">
                                                    {{ $task->due_date?->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-4 px-4 border border-dashed border-gray-700/50 rounded-lg">
                                        <p class="text-xs text-gray-600 italic">No tienes actividades asignadas para hoy.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    {{-- GRUPO 2: TAREAS DONDE SOY SOLICITANTE --}}
                    <div>
                        <div class="flex items-center gap-4 mb-8 {{ $isWorkerOrAdmin ? 'pt-2' : '' }}">
                            <div
                                class="p-2.5 bg-emerald-500/20 rounded-lg border border-emerald-500/30 shadow-[0_0_15px_-3px_rgba(16,185,129,0.5)]">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <span class="text-xs text-white font-bold uppercase tracking-widest">Mis Seguimientos</span>
                            <div class="h-[1px] bg-emerald-500/30 flex-1"></div>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            @forelse ($urgentTasks->where('requester', auth()->id())->where('responsible', '!=', auth()->id()) as $task)
                                <div
                                    class="bg-gray-900/40 border border-gray-700/50 rounded-lg p-3 hover:bg-gray-900/60 transition-all">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-white text-sm font-medium truncate">{{ $task->title }}</h4>
                                            <p class="text-[10px] text-gray-400 uppercase mt-1 tracking-tighter">Atiende:
                                                <span
                                                    class="text-emerald-400/80">{{ $task->responsibleUser->name ?? 'Pendiente' }}</span>
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <span
                                                class="text-[8px] px-2 py-0.5 rounded bg-blue-500/10 text-blue-500 border border-blue-500/20 font-bold uppercase">{{ str_replace('_', ' ', $task->status) }}</span>
                                            <p class="text-[11px] font-mono text-gray-400">
                                                {{ $task->due_date?->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 px-4 border border-dashed border-gray-700/50 rounded-lg">
                                    <p class="text-xs text-gray-600 italic">No tienes solicitudes externas en curso.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(79, 70, 229, 0.2);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(79, 70, 229, 0.4);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('statusChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const dataCounts = [
                parseInt("{{ $statusCounts['por_hacer'] ?? 0 }}"),
                parseInt("{{ $statusCounts['haciendo'] ?? 0 }}"),
                parseInt("{{ $statusCounts['hecho'] ?? 0 }}"),
                parseInt("{{ $statusCounts['cancelado'] ?? 0 }}")
            ];
            const colors = ['#eab308', '#3b82f6', '#22c55e', '#ef4444'];
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Por Hacer', 'Haciendo', 'Hecho', 'Cancelado'],
                    datasets: [{
                        data: dataCounts,
                        backgroundColor: colors,
                        borderColor: 'transparent',
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af',
                                padding: 25,
                                font: {
                                    size: 11,
                                    family: 'monospace'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
