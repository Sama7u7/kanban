@extends('layouts.test')

@section('content')
    <div x-data="{
        showCreateModal: false,
        showEditModal: false,
        roleToEdit: { id: null, display_name: '', permissions: [] }
    }">

        <h1 class="text-3xl md:text-4xl text-white font-mono pb-8">Roles</h1>

        <div class="border rounded-xl border-gray-700 bg-[#161615]/50 px-4 py-6 md:px-6">

            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                <h2 class="text-xl text-gray-400 font-semibold text-center md:text-left">Gestión de Roles</h2>

                <button @click="showCreateModal = true"
                    class="w-full md:w-auto bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                    + Crear nuevo rol
                </button>
            </div>

            <div class="overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <table class="w-full text-white border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-gray-500 text-sm uppercase tracking-wider">
                            <th class="px-4 py-2 text-left">Nombre del Rol</th>
                            <th class="px-4 py-2 text-center">Permisos Asignados</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm md:text-base">
                        @forelse ($roles as $role)
                            <tr class="bg-[#1b1b18] hover:bg-[#252522] transition-colors rounded-lg">
                                <td class="px-4 py-4 rounded-l-lg font-medium">{{ $role->display_name }}</td>

                                <td class="px-4 py-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs border bg-indigo-500/10 text-indigo-400 border-indigo-500/20">
                                        {{ $role->permissions->count() }} permisos
                                    </span>
                                </td>

                                <td class="px-4 py-4 rounded-r-lg">
                                    <div class="flex items-center justify-center gap-4">

                                        <button
                                            @click="
                                            roleToEdit = { 
                                                id: '{{ $role->id }}', 
                                                display_name: '{{ $role->display_name }}',
                                                permissions: {{ $role->permissions->pluck('id') }}
                                            }; 
                                            showEditModal = true;
                                        "
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                                            </svg>
                                        </button>

                                        @if ($role->name !== 'admin')
                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                                onsubmit="return confirm('¿Eliminar rol?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-400 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <div class="w-5 h-5"></div>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-10 text-center text-gray-500">No hay roles registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/80" @click="showCreateModal = false"></div>
            <div class="bg-[#1b1b18] border border-gray-700 w-full max-w-md p-6 rounded-xl z-50">
                <h2 class="text-white text-xl mb-6 font-semibold">Nuevo Rol</h2>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="text-gray-400 text-sm block mb-2">Nombre del Rol</label>
                        <input type="text" name="display_name" required
                            class="w-full bg-[#161615] border-gray-700 border rounded-lg p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label class="text-gray-400 text-sm block mb-2">Permisos asignados:</label>
                        <div class="grid grid-cols-1 gap-2 max-h-40 overflow-y-auto pr-2">
                            @foreach ($permissions as $p)
                                <label class="flex items-center space-x-3 text-gray-300">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                        class="rounded bg-[#161615] border-gray-600 text-indigo-500 focus:ring-indigo-500">
                                    <span class="text-sm">{{ $p->description }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showCreateModal = false"
                            class="text-gray-400 hover:text-white px-4 py-2">Cancelar</button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/80" @click="showEditModal = false"></div>
            <div class="bg-[#1b1b18] border border-gray-700 w-full max-w-md p-6 rounded-xl z-50">
                <h2 class="text-white text-xl mb-6 font-semibold">Editar Rol</h2>
                <form :action="'/roles/' + roleToEdit.id" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="text-gray-400 text-sm block mb-2">Nombre</label>
                        <input type="text" name="display_name" x-model="roleToEdit.display_name" required
                            class="w-full bg-[#161615] border-gray-700 border rounded-lg p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label class="text-gray-400 text-sm block mb-2">Permisos:</label>
                        <div class="grid grid-cols-1 gap-2 max-h-40 overflow-y-auto pr-2">
                            @foreach ($permissions as $p)
                                <label class="flex items-center space-x-3 text-gray-300">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                        :checked="roleToEdit.permissions.includes({{ $p->id }})"
                                        class="rounded bg-[#161615] border-gray-600 text-indigo-500 focus:ring-indigo-500">
                                    <span class="text-sm">{{ $p->description }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showEditModal = false"
                            class="text-gray-400 hover:text-white px-4 py-2">Cancelar</button>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
