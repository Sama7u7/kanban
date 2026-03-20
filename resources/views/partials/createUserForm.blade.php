{{-- Cambiamos hidden por x-show y añadimos flex para centrado --}}
<div id="modalCrear" 
     x-show="showCreateModalUser" {{-- Asumiendo que definas esta variable en tu x-data --}}
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/70 flex items-center justify-center z-100 p-4"
     style="display: none;"> {{-- Evita parpadeo al cargar --}}

    {{-- Contenedor blanco: max-h-screen y overflow-y-auto para móviles --}}
    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

        {{-- Header del modal --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Nueva actividad</h2>
            <button @click="showCreateModalUser = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light leading-none">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="title" required
                           class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>


                {{-- Grid para campos pequeños en escritorio --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
                        <select required name="role" class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            <option value="" disabled selected>Selecciona un rol</option>
                                <option value="it">IT</option>
                                <option value="padre_familia">Padre de familia</option>
                                <option value="profesor">Profesor</option>
                                <option value="seccion_prim">Seccion primaria</option>
                                <option value="seccion_sec">Seccion secundaria</option>
                                <option value="seccion_prep">Seccion preparatoria</option>
                                <option value="seccion_pres">Seccion preescolar</option>
                        </select>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo electronico</label>
                        <input type="text" name="email" required
                               class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                    </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showCreateModal = false"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-md hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition">
                    Guardar Actividad
                </button>
            </div>
        </form>
    </div>
</div>