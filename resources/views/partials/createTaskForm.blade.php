{{-- Cambiamos hidden por x-show y añadimos flex para centrado --}}
<div id="modalCrear" 
     x-show="showCreateModal" {{-- Asumiendo que definas esta variable en tu x-data --}}
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
            <button @click="showCreateModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light leading-none">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="space-y-4">
                {{-- Título --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="title" required
                           class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea name="description" rows="3"
                              class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>

                {{-- Grid para campos pequeños en escritorio --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable</label>
                        <select required name="responsible" class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            <option value="" disabled selected>Selecciona responsable</option>
                            @foreach ($itUsers as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Solicitante</label>
                        <select required name="requester" class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            <option value="" disabled selected>Selecciona quien solicita</option>
                            @foreach ($nonItUsers as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha límite</label>
                        <input type="date" name="due_date" required
                               class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estatus</label>
                        <select name="status" class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            <option value="por_hacer">🟡 Por hacer</option>
                            <option value="haciendo">🔵 Haciendo</option>
                            <option value="hecho">🟢 Hecho</option>
                            <option value="cancelado">🔴 Cancelado</option>
                        </select>
                    </div>
                </div>
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