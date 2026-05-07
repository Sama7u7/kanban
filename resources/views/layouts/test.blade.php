<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <title>Taskify</title>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/anchor@3.x.x/dist/cdn.min.js"></script>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background h-screen overflow-hidden flex flex-col lg:flex-row" x-data="{
    open: false,

    /* 1. MEMORIA DEL NAVEGADOR: Lee el localStorage. Si dice 'false', arranca cerrada. Si no, abierta. */
    sidebarExpanded: localStorage.getItem('sidebarExpanded') === 'false' ? false : true,

    showPasswordConfirm: false,
    showPassword: false,
    showCreateModal: false,
    showCreateModalUser: false,
    showEditModal: false,
    showEditModalUser: false,
    showViewModal: false,
    showViewModalUser: false,
    selectedTask: {},
    selectedUser: {},
    newPassword: '',
}"
    x-init="/* 2. EL VIGÍA: Si le das clic al botón de expandir/contraer, guarda la decisión en la memoria */
    $watch('sidebarExpanded', value => localStorage.setItem('sidebarExpanded', value));
    
    $nextTick(() => {
        showEditModalUser = false;
        showViewModalUser = false;
    });">
    @include ('components.toasts')

    {{-- HEADER MÓVIL (No se toca) --}}
    <header class="lg:hidden bg-gray-800 text-white p-4 flex justify-between items-center shrink-0">
        <img src="{{ asset('images/logo-in-app.svg') }}" class="h-8 w-auto" />
        <button @click="open = true" class="p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m7 6H4"></path>
            </svg>
        </button>
    </header>

    {{-- ASIDE (Barra Lateral) --}}
    <aside
        :class="[
            open ? 'translate-x-0' : '-translate-x-full',
            sidebarExpanded ? 'lg:w-64' : 'lg:w-20' /* <-- AJUSTA EL ANCHO DINÁMICAMENTE */
        ]"
        class="fixed inset-y-0 left-0 z-50 bg-gray-800 shrink-0 flex flex-col transition-all duration-300 transform lg:translate-x-0 lg:static overflow-hidden">
        <div class="lg:hidden flex justify-end p-4">
            <button @click="open = false" class="text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        @include ('partials.header')
    </aside>

    {{-- FONDO OSCURO MÓVIL (No se toca) --}}
    <div x-show="open" x-transition:enter="transition opacity-100 duration-300"
        x-transition:leave="transition opacity-0 duration-300" @click="open = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <div class="flex-1 flex flex-col overflow-hidden w-full">
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield ('content')
        </main>
        @stack ('modals')
    </div>
</body>

</html>
