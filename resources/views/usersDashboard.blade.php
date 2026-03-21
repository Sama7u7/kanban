@extends ('layouts.test')

@section ('content')
    <h1 class="text-3xl md:text-4xl text-white font-mono pb-8">Tareas</h1>
    <div
        class="border rounded-xl border-gray-700 bg-[#161615]/50 px-4 py-6 md:px-6"
    >
        <div
            class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8"
        >
            <h2
                class="text-xl text-gray-400 font-semibold text-center md:text-left"
            >
                Gestión de usuarios
            </h2>

            {{-- Botón Crear Actividad --}}
            <button
                @click="showCreateModalUser = true"
                class="w-full md:w-auto bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20"
            >
                + Crear un usuario
            </button>
        </div>

        {{-- Contenedor de tabla responsivo --}}
        <div
            class="overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        >
            <table class="w-full text-white border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-gray-500 text-sm uppercase tracking-wider">
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2 hidden md:table-cell">Correo</th>
                    </tr>
                </thead>
                <tbody class="text-sm md:text-base">
                    @forelse ($users as $user)
                        <tr
                            class="bg-[#1b1b18] hover:bg-[#252522] transition-colors rounded-lg"
                        >
                            <td class="px-4 py-4 rounded-l-lg font-medium">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $roles = [
                                        'it' => 'IT',
                                        'padre_familia' => 'Padre de familia',
                                        'profesor' => 'Profesor',
                                        'seccion_prim' => 'Sección primaria',
                                        'seccion_sec' => 'Sección secundaria',
                                        'seccion_prep' => 'Sección preparatoria',
                                        'seccion_pres' => 'Sección preescolar',
                                    ];
                                @endphp
                                {{ $roles[$user->role] ?? $user->role }}
                            </td>
                            <td
                                class="px-4 py-4 text-center hidden md:table-cell"
                            >
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-4 rounded-r-lg">
                                <div
                                    class="flex items-center justify-center gap-4"
                                >
                                    {{-- Ver Info: Cargamos datos en selecteduser --}}
                                    <button
                                        @click="selectedUser = @js($user); showViewModalUser = true"
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    {{-- Editar: Cargamos datos en selectedUser --}}
                                    <button
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                        @click="selectedUser = { 
                                        id: {{ $user->id }}, 
                                        name: @js($user->name), 
                                        role: @js($user->role), 
                                        email: @js($user->email)
                                    }; showEditModalUser = true; $nextTick(() => { showEditModalUser = true })"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                                        </svg>
                                    </button>

                                    {{-- Eliminar --}}
                                    <form
                                        method="POST"
                                        action="{{ route('users.destroy', $user->id) }}"
                                        onsubmit="
                                            return confirm(
                                                '¿Eliminar actividad?',
                                            );
                                        "
                                    >
                                        @csrf
                                        @method ('DELETE')
                                        <button
                                            type="submit"
                                            class="text-red-500 hover:text-red-400 transition-colors"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="py-10 text-center text-gray-500"
                            >
                                No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">{{ $users->links() }}</div>
    </div>
    @include ('partials.editUserForm')
    @include ('partials.viewUserInfo')
    @include ('partials.createUserForm')
@endsection
