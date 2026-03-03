<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <title>Taskify</title>

            <!-- Styles / Scripts -->
                @vite(['resources/css/app.css', 'resources/js/app.js'])


    </head>

<body class="bg-background h-screen overflow-hidden flex flex-col lg:flex-row" x-data="{ open: false, showCreateModal: false, showEditModal: false, showViewModal: false,selectedTask: {} }">

    @include('components.toasts')

    <header class="lg:hidden bg-gray-800 text-white p-4 flex justify-between items-center shrink-0">
        <img src="{{ asset('images/logo-in-app.svg') }}" class="h-8 w-auto"/>
        <button @click="open = true" class="p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m7 6H4"></path></svg>
        </button>
    </header>

    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 shrink-0 flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:static">
        
        <div class="lg:hidden flex justify-end p-4">
            <button @click="open = false" class="text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        @include('partials.header')
    </aside>

    <div x-show="open" 
         x-transition:enter="transition opacity-100 duration-300"
         x-transition:leave="transition opacity-0 duration-300"
         @click="open = false" 
         class="fixed inset-0 bg-black/50 z-40 lg:hidden">
    </div>

    <div class="flex-1 flex flex-col overflow-hidden w-full">
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    {{-- MODALES: Fuera del main para que se centren bien --}}
    @include('partials.createTaskForm')
    @include('partials.editTaskForm')
    @include('partials.viewTaskInfo')
</body>
</html>
