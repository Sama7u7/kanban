<nav class="h-full flex flex-col px-4 py-6 gap-2">
    {{-- Logo y Botón Cerrar (solo móvil) --}}
    <div class="mb-6 flex justify-between items-center">
        <img src="{{ asset('images/logo-in-app.svg') }}" class="h-8 w-auto"/>
        
    </div>

    {{-- Links --}}
    <div class="flex flex-col gap-1">
        <a href="{{ route('tasks.index') }}"
           class="flex items-center rounded-md px-3 py-2.5 text-sm font-medium text-white bg-gray-950/50">
           Dashboard
        </a>
        <a href="#"
           class="flex items-center rounded-md px-3 py-2.5 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
           Team
        </a>
        <a href="#"
           class="flex items-center rounded-md px-3 py-2.5 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition-colors">
           Projects
        </a>
    </div>

    {{-- Logout al fondo --}}
    <div class="mt-auto border-t border-gray-700 pt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full text-left rounded-md px-3 py-2.5 text-sm font-medium text-red-400 hover:bg-red-500/10 transition-colors">
                Logout
            </button>
        </form>
    </div>
</nav>