@extends('layouts.test')

@section('content')
    {{-- Contenedor principal con el estado de Alpine.js --}}
    <div x-data="{
        showCreateModalUser: false,
        showEditModalUser: false,
        showViewModalUser: false,
        showPassword: false,
        showPasswordConfirm: false,
        changePassword: false,
        selectedUser: { id: null, name: '', email: '', roles: [] },
    
        // Estado para los selects dinámicos de roles
        userRoles: [{ id: '' }],
    
        addRoleField() {
            this.userRoles.push({ id: '' });
        },
    
        removeRoleField(index) {
            if (this.userRoles.length > 1) {
                this.userRoles.splice(index, 1);
            }
        },
    
        openCreateModal() {
            this.userRoles = [{ id: '' }];
            this.showCreateModalUser = true;
        },
    
        openEditModal(user) {
            this.selectedUser = user;
            this.userRoles = user.roles.length > 0 ?
                user.roles.map(r => ({ id: r.id })) : [{ id: '' }];
            this.changePassword = false;
            this.showEditModalUser = true;
        },
    
        generateSecurePassword() {
            // Versión amigable: 8 caracteres, sin letras confusas
            const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#';
            let pass = '';
            for (let i = 0; i < 8; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return pass;
        },
    
        fillPassword(type) {
            const newPass = this.generateSecurePassword();
            if (type === 'create') {
                this.$refs.passCreate.value = newPass;
                this.$refs.passConfirmCreate.value = newPass;
            } else {
                this.$refs.passEdit.value = newPass;
            }
            alert('Contraseña generada: ' + newPass);
        }
    }" x-cloak>

        <h1 class="text-3xl md:text-4xl text-white font-mono pb-8">Usuarios</h1>

        <div class="border rounded-xl border-gray-700 bg-[#161615]/50 px-4 py-6 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                <h2 class="text-xl text-gray-400 font-semibold text-center md:text-left">Gestión de Usuarios</h2>
                <button @click="openCreateModal()"
                    class="w-full md:w-auto bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                    + Crear un usuario
                </button>
            </div>

            <div class="overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <table class="w-full text-white border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-gray-500 text-sm uppercase tracking-wider">
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-center">Rol(es)</th>
                            <th class="px-4 py-2 hidden md:table-cell text-center">Correo</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm md:text-base">
                        @forelse ($users as $user)
                            <tr class="bg-[#1b1b18] hover:bg-[#252522] transition-colors rounded-lg">
                                <td class="px-4 py-4 rounded-l-lg font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-4">
                                    {{-- Corregido: gap-2 y flex-wrap para badges --}}
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @foreach ($user->roles as $role)
                                            <span
                                                class="px-3 py-1 rounded-full text-[10px] font-bold border border-indigo-500/20 bg-indigo-500/10 text-indigo-400 uppercase tracking-widest whitespace-nowrap">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center hidden md:table-cell text-gray-400 font-mono">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-4 rounded-r-lg">
                                    <div class="flex items-center justify-center gap-4">
                                        <button
                                            @click="selectedUser = @js($user); showViewModalUser = true"
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>

                                        <button @click="openEditModal(@js($user))"
                                            class="text-indigo-400 hover:text-indigo-300 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                                            </svg>
                                        </button>

                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                            onsubmit="return confirm('¿Eliminar usuario?')">
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
                                <td colspan="4" class="py-10 text-center text-gray-500 italic">No hay usuarios
                                    registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">{{ $users->links() }}</div>
        </div>

        {{-- ==========================================
             MODAL: CREAR USUARIO
             ========================================== --}}
        <x-modal-base id="modalCrearUser" title="Nuevo Usuario" showVariable="showCreateModalUser">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Nombre
                        Completo</label>
                    <input type="text" name="name" required
                        class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Correo
                        Electrónico</label>
                    <input type="email" name="email" required
                        class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                {{-- Roles Dinámicos --}}
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Asignar
                        Roles</label>
                    <template x-for="(roleField, index) in userRoles" :key="index">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 bg-[#1b1b18] border border-gray-700 rounded-lg">
                                <select name="roles[]" x-model="roleField.id" required
                                    class="w-full bg-transparent text-white px-3 py-2 outline-none rounded-lg text-sm">
                                    <option value="" disabled class="bg-[#161615]">Seleccionar rol...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" class="bg-[#161615]">{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="removeRoleField(index)" x-show="userRoles.length > 1"
                                class="text-red-500 p-2 hover:bg-red-500/10 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addRoleField()"
                        class="text-[10px] font-bold text-indigo-400 hover:underline uppercase">+ Añadir otro rol</button>
                </div>

                {{-- Sección Password Alineada --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-1">
                            <label
                                class="text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Contraseña</label>
                            <button type="button" @click="fillPassword('create')"
                                class="text-[9px] text-indigo-400 hover:underline font-bold uppercase">Generar</button>
                        </div>
                        <input x-ref="passCreate" type="password" name="password" required
                            class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="flex flex-col">
                        <div class="mb-1 flex items-center h-[14px]">
                            <label
                                class="text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Confirmar</label>
                        </div>
                        <input x-ref="passConfirmCreate" type="password" name="password_confirmation" required
                            class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                    <button type="button" @click="showCreateModalUser = false"
                        class="text-gray-500 text-xs font-mono uppercase px-4 py-2 hover:text-white">Cancelar</button>
                    <button type="submit" :disabled="loading"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                        <span x-show="!loading">Guardar Usuario</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </div>
            </form>
        </x-modal-base>

        {{-- ==========================================
             MODAL: EDITAR USUARIO
             ========================================== --}}
        <x-modal-base id="modalEditUser" title="Editar Usuario" showVariable="showEditModalUser">
            <form method="POST" :action="'/users/' + selectedUser.id" class="space-y-4" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf @method('PUT')
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Nombre</label>
                    <input type="text" name="name" x-model="selectedUser.name" required
                        class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Correo</label>
                    <input type="email" name="email" x-model="selectedUser.email" required
                        class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none">
                </div>

                {{-- Roles en Edición --}}
                <div class="space-y-2">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Roles
                        Asignados</label>
                    <template x-for="(roleField, index) in userRoles" :key="index">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 bg-[#1b1b18] border border-gray-700 rounded-lg">
                                <select name="roles[]" x-model="roleField.id" required
                                    class="w-full bg-transparent text-white px-3 py-2 outline-none rounded-lg text-sm">
                                    <option value="" disabled class="bg-[#161615]">Seleccionar rol...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" class="bg-[#161615]">{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" @click="removeRoleField(index)" x-show="userRoles.length > 1"
                                class="text-red-500 p-2 hover:bg-red-500/10 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addRoleField()"
                        class="text-[10px] font-bold text-indigo-400 hover:underline uppercase">+ Añadir otro rol</button>
                </div>

                <div class="pt-2 border-t border-gray-800/50">
                    <div class="flex items-center gap-2 mb-3">
                        <input type="checkbox" id="changePass" x-model="changePassword"
                            class="rounded border-gray-700 bg-[#1b1b18] text-indigo-600 focus:ring-0">
                        <label for="changePass"
                            class="text-[10px] uppercase tracking-widest text-gray-400 font-mono cursor-pointer">¿Actualizar
                            Contraseña?</label>
                    </div>

                    <div x-show="changePassword" x-transition class="space-y-1">
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Nueva
                                Clave</label>
                            <button type="button" @click="fillPassword('edit')"
                                class="text-[9px] text-indigo-400 hover:underline font-bold uppercase">Generar</button>
                        </div>
                        <input x-ref="passEdit" type="text" name="password" placeholder="Mínimo 8 caracteres"
                            class="w-full bg-[#1b1b18] border border-gray-700 text-white rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-800">
                    <button type="button" @click="showEditModalUser = false"
                        class="text-gray-500 text-xs font-mono uppercase px-4 py-2 hover:text-white">Cancelar</button>
                    <button type="submit" :disabled="loading"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-indigo-700">
                        <span x-show="!loading">Actualizar Usuario</span>
                        <span x-show="loading">Procesando...</span>
                    </button>
                </div>
            </form>
        </x-modal-base>

        {{-- MODAL: VER DETALLES --}}
        <x-modal-base id="modalViewUser" title="Detalles del Usuario" showVariable="showViewModalUser">
            <div class="space-y-4 py-2">
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Nombre
                        Completo</label>
                    <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2 text-white font-semibold"
                        x-text="selectedUser.name"></div>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Correo
                        Electrónico</label>
                    <div class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-2 text-white font-mono"
                        x-text="selectedUser.email"></div>
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] uppercase tracking-widest text-indigo-400 font-mono">Roles
                        Asignados</label>
                    <div
                        class="w-full border border-gray-700 bg-[#1b1b18] rounded-lg px-3 py-3 flex flex-wrap gap-2 items-center min-h-[45px]">
                        <template x-for="role in selectedUser.roles" :key="role.id">
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-bold border border-green-500/20 bg-green-500/10 text-green-400 uppercase tracking-widest"
                                x-text="role.name"></span>
                        </template>
                    </div>
                </div>
                <div class="flex justify-end pt-6 border-t border-gray-800">
                    <button type="button" @click="showViewModalUser = false"
                        class="bg-gray-800 hover:bg-gray-700 text-gray-300 text-[10px] uppercase tracking-widest font-mono px-8 py-2.5 rounded-lg border border-gray-700 transition-all">Cerrar
                        Panel</button>
                </div>
            </div>
        </x-modal-base>

    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
