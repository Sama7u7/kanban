<!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
<nav class="h-full flex flex-col px-4 py-6 gap-2">

    {{-- Logo --}}
    <div class="mb-6">
        <img src="{{ asset('images/logo-in-app.svg') }}" class="h-8 w-auto"/>
    </div>

    {{-- Links --}}
    <a href="{{ route('tasks.index') }}"
       class="rounded-md px-3 py-2 text-sm font-medium text-white bg-gray-950/50">
        Dashboard
    </a>
    <a href="#"
       class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
        Team
    </a>
    <a href="#"
       class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
        Projects
    </a>

    {{-- Logout al fondo --}}
    <div class="mt-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full text-left rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                Logout
            </button>
        </form>
    </div>

</nav>
