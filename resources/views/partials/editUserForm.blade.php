<div
    x-show="showEditModalUser"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 bg-black/70 flex items-center justify-center z-100 p-4"
    x-cloak
>
    <div
        class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl"
    >
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Editar usuario
            </h2>
            <button
                @click="showEditModalUser = false"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light leading-none"
            >
                &times;
            </button>
        </div>

        <form method="POST" :action="`/users/${selectedUser.id}`">
            @csrf
            @method ('PUT')

            <div class="space-y-4 text-left">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Nombre</label
                    >
                    <input
                        type="text"
                        name="name"
                        required
                        x-model="selectedUser.name"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Rol</label
                    >
                    <select
                        name="role"
                        required
                        x-model="selectedUser.role"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2"
                    >
                        <option value="it">IT</option>
                        <option value="padre_familia">Padre de familia</option>
                        <option value="profesor">Profesor</option>
                        <option value="seccion_prim">Sección primaria</option>
                        <option value="seccion_sec">Sección secundaria</option>
                        <option value="seccion_prep">
                            Sección preparatoria
                        </option>
                        <option value="seccion_pres">Sección preescolar</option>
                    </select>
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Correo electrónico</label
                    >
                    <input
                        type="email"
                        name="email"
                        required
                        x-model="selectedUser.email"
                        class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Nueva contraseña
                        <span class="text-gray-400 text-xs"
                            >(dejar vacío para no cambiar)</span
                        >
                    </label>
                    <div class="relative mt-1">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 pr-10"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="
                                    !showPassword
                                " class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="
                                    showPassword
                                " class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Confirmar nueva contraseña</label
                    >
                    <div class="relative mt-1">
                        <input
                            :type="showPasswordConfirm ? 'text' : 'password'"
                            name="password_confirmation"
                            class="w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 pr-10"
                        />
                        <button
                            type="button"
                            @click="showPasswordConfirm = !showPasswordConfirm"
                            class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                        >
                            <svg x-show="
                                    !showPasswordConfirm
                                " class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="
                                    showPasswordConfirm
                                " class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button
                    type="button"
                    @click="showEditModalUser = false"
                    class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-md hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition"
                >
                    Actualizar usuario
                </button>
            </div>
        </form>
    </div>
</div>
