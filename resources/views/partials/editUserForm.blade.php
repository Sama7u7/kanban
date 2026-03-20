{{-- Cambiamos hidden por x-show y conectamos al estado de Alpine --}}
<div id="modalEditar" 
     x-show="showEditModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/70 flex items-center justify-center z-100 p-4"
     style="display: none;">

    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Editar actividad</h2>
            <button @click="showEditModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light leading-none">
                &times;
            </button>
        </div>

        {{-- El action del form ahora es dinámico gracias a Alpine --}}
        <form method="POST" :action="`/tasks/${selectedTask.id}`">
            @csrf
            @method('PUT')

            <div class="space-y-4 text-left">
                {{-- Título --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="title" required
                           x-model="selectedTask.title"
                           class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                    <textarea name="description" rows="3"
                              x-model="selectedTask.description"
                              class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                {{-- Grid Responsivo --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsable</label>
                        <select required name="responsible" x-model="selectedTask.responsible"
                                class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            @foreach ($itUsers as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Solicitante</label>
                        <select required name="requester" x-model="selectedTask.requester"
                                class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            @foreach ($nonItUsers as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha límite</label>
                        {{-- Nota: selectedTask.due_date debe venir en formato YYYY-MM-DD --}}
                        <input type="date" name="due_date" 
                               x-model="selectedTask.due_date"
                               class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estatus</label>
                        <select name="status" x-model="selectedTask.status"
                                class="mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-md px-3 py-2">
                            <option value="por_hacer">🟡 Por hacer</option>
                            <option value="haciendo">🔵 Haciendo</option>
                            <option value="hecho">🟢 Hecho</option>
                            <option value="cancelado">🔴 Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="showEditModal = false"
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md">
                    Cancelar
                </button>
                <button type="submit"
                        class="bg-indigo-600 text-white font-bold px-6 py-2 rounded-md hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition">
                    Actualizar Actividad
                </button>
            </div>
        </form>
    </div>
</div>