<nav class="h-full flex flex-col px-4 py-6 gap-2">

    {{-- Logo y Botón de Contraer --}}
    <div class="mb-8 flex transition-all duration-300"
        :class="sidebarExpanded ? 'flex-row justify-between items-center px-1' :
            'flex-col justify-center items-center gap-4 px-0 mt-2'">

        <div class="flex items-center justify-center shrink-0">
            <img x-show="sidebarExpanded" src="{{ asset('images/logo-in-app.svg') }}" class="h-8 w-auto shrink-0"
                alt="Logo" />

            <img x-show="!sidebarExpanded" x-cloak src="{{ asset('images/icono.svg') }}"
                class="h-8 w-8 shrink-0 object-contain" alt="Icono" />
        </div>

        <button @click="sidebarExpanded = !sidebarExpanded"
            class="hidden lg:block text-gray-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-gray-700 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>

    {{-- Links --}}
    <div class="flex flex-col gap-2">

        {{-- Dashboard (Corregido el routeIs) --}}
        <a href="{{ route('dashboard') }}" title="Dashboard" @class([
            'flex items-center gap-3 rounded-md px-3 py-2.5 text-sm transition-all',
            'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/50' => request()->routeIs(
                'dashboard'),
            'font-medium text-gray-300 hover:bg-white/5 hover:text-white' => !request()->routeIs(
                'dashboard'),
        ])>
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="sidebarExpanded" class="truncate">Dashboard</span>
        </a>

        {{-- Tareas (NUEVO BOTÓN PÚBLICO) --}}
        <a href="{{ route('tasks.index') }}" title="Tareas" @class([
            'flex items-center gap-3 rounded-md px-3 py-2.5 text-sm transition-all',
            'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/50' => request()->routeIs(
                'tasks.*'),
            'font-medium text-gray-300 hover:bg-white/5 hover:text-white' => !request()->routeIs(
                'tasks.*'),
        ])>
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span x-show="sidebarExpanded" class="truncate">Tareas</span>
        </a>

        @can('manage_users')
            {{-- Usuarios --}}
            <a href="{{ route('users.index') }}" title="Usuarios" @class([
                'flex items-center gap-3 rounded-md px-3 py-2.5 text-sm transition-all',
                'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/50' =>
                    request()->is('users*') || request()->routeIs('users.*'),
                'font-medium text-gray-300 hover:bg-white/5 hover:text-white' =>
                    !request()->is('users*') && !request()->routeIs('users.*'),
            ])>
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="sidebarExpanded" class="truncate">Usuarios</span>
            </a>
        @endcan

        @can('manage_roles')
            {{-- Roles --}}
            <a href="{{ route('roles.index') }}" title="Gestión de roles" @class([
                'flex items-center gap-3 rounded-md px-3 py-2.5 text-sm transition-all',
                'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/50' =>
                    request()->is('roles*') || request()->routeIs('roles.*'),
                'font-medium text-gray-300 hover:bg-white/5 hover:text-white' =>
                    !request()->is('roles*') && !request()->routeIs('roles.*'),
            ])>
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span x-show="sidebarExpanded" class="truncate">Gestión de roles</span>
            </a>
        @endcan
    </div>

    {{-- Info del Usuario --}}
    <div class="mt-auto pb-4">
        <div class="flex items-center gap-3 px-1">
            <div
                class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-lg shadow-indigo-500/30">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div x-show="sidebarExpanded" class="flex flex-col overflow-hidden">
                <span class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</span>
                <span class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>

    {{-- Logout --}}
    <div class="border-t border-gray-700 pt-3 pb-2">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" title="Logout"
                class="w-full flex items-center gap-3 rounded-md px-2 py-2.5 text-sm font-medium text-red-400 hover:bg-red-500/10 transition-colors">
                <svg class="w-5 h-5 shrink-0 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span x-show="sidebarExpanded" class="truncate">Logout</span>
            </button>
        </form>
    </div>
</nav>
