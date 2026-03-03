<div id="modalVer" 
     x-show="showViewModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/70 flex items-center justify-center z-100 p-4"
     style="display: none;">

    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Detalles de la actividad</h2>
            <button @click="showViewModal = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light">
                &times;
            </button>
        </div>

        <div class="space-y-4">
            {{-- Título --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</label>
                <p x-text="selectedTask.title" class="text-gray-700 dark:text-gray-200 mt-1 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5 font-medium"></p>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Descripción</label>
                <p x-text="selectedTask.description || 'Sin descripción'" 
                   class="text-gray-700 dark:text-gray-200 mt-1 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5 whitespace-pre-wrap min-h-20"></p>
            </div>

            {{-- Grid Responsivo para detalles --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Responsable</label>
                    <p x-text="selectedTask.responsible" class="text-gray-700 dark:text-gray-200 mt-1 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5"></p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Solicitante</label>
                    <p x-text="selectedTask.requester" class="text-gray-700 dark:text-gray-200 mt-1 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5"></p>
                </div>
            </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Fecha límite</label>
                    <p x-text="selectedTask.due_date ? selectedTask.due_date.split('T')[0] : 'Sin fecha'" 
                        class="text-gray-700 dark:text-gray-200 mt-1 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-2.5">
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estatus</label>
                    <div class="mt-1 flex">
                        <span x-text="{
                                'por_hacer': '🟡 Por hacer',
                                'haciendo': '🔵 Haciendo',
                                'hecho': '🟢 Hecho',
                                'cancelado': '🔴 Cancelado'
                              }[selectedTask.status] || selectedTask.status"
                              class="px-3 py-2 w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-gray-700 dark:text-gray-200">
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button @click="showViewModal = false"
                    class="w-full md:w-auto bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white font-bold px-8 py-3 rounded-lg hover:bg-gray-200 dark:hover:bg-white/20 transition-all">
                Cerrar
            </button>
        </div>
    </div>
</div>