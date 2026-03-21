<div
    x-show="showViewModalUser"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 bg-black/70 flex items-center justify-center z-100 p-4"
>
    <div
        class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl"
    >
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Detalles del usuario
            </h2>
            <button
                @click="showViewModalUser = false"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light"
            >
                &times;
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label
                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                    >Nombre</label
                >
                <p x-text="selectedUser.name" class="text-gray-700 dark:text-gray-200 mt-1 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5 font-medium"></p>
            </div>

            <div>
                <label
                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                    >Rol</label
                >
                <p
                    x-text="
                        {
                            it: 'IT',
                            padre_familia: 'Padre de familia',
                            profesor: 'Profesor',
                            seccion_prim: 'Sección primaria',
                            seccion_sec: 'Sección secundaria',
                            seccion_prep: 'Sección preparatoria',
                            seccion_pres: 'Sección preescolar',
                        }[selectedUser.role] || selectedUser.role
                    "
                    class="text-gray-700 dark:text-gray-200 mt-1 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5"
                ></p>
            </div>

            <div>
                <label
                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                    >Correo electrónico</label
                >
                <p
                    x-text="selectedUser.email || 'Sin correo'"
                    class="text-gray-700 dark:text-gray-200 mt-1 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5"
                ></p>
            </div>
        </div>
        <div class="mt-4 border-t border-gray-200 dark:border-white/10 pt-4">
            <label
                class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2"
            >
                Resetear contraseña
            </label>

            <div class="flex gap-2 mb-2">
                <input
                    type="text"
                    x-model="newPassword"
                    placeholder="Nueva contraseña"
                    class="flex-1 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 text-sm"
                />
                <button
                    type="button"
                    @click="newPassword = Math.random().toString(36).slice(-6)"
                    class="bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white px-3 py-2 rounded-md text-sm hover:bg-gray-300 transition"
                >
                    Generar
                </button>
            </div>

            <form
                method="POST"
                :action="`/users/${selectedUser.id}/reset-password`"
            >
                @csrf
                @method ('PATCH')
                <input type="hidden" name="password" :value="newPassword" />
                <button
                    type="submit"
                    class="mt-2 w-full bg-indigo-600 text-white font-bold px-4 py-2 rounded-md hover:bg-indigo-700 transition text-sm"
                >
                    Aplicar contraseña
                </button>
            </form>
        </div>

        <div class="mt-6 flex justify-end">
            <button
                @click="showViewModalUser = false"
                class="w-full md:w-auto bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white font-bold px-8 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-white/20 transition-all"
            >
                Cerrar
            </button>
        </div>
    </div>
</div>
