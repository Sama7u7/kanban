                                {{-- Ventana modal boton editar --}}
                                <div id="modalEditar" class="hidden fixed inset-0 bg-black/70  flex items-center justify-center z-50">
                                    <div class="bg-white rounded-xl p-6 w-full max-w-lg">

                                        {{-- Header del modal con botón X --}}
                                        <div class="flex justify-between items-center mb-4">
                                            <h2 class="text-xl font-bold text-gray-400">Editar actividad</h2>
                                            <button onclick="document.getElementById('modalEditar').classList.add('hidden')"
                                                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                                                &times;
                                            </button>
                                        </div>

                                        <form method="POST" id="formEditar" action="">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Título</label>
                                                <input type="text" name="title" id="editTitle" required
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                                <textarea name="description" id="editDescription" rows="3"
                                                        class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Responsable</label>
                                                <input type="text" name="responsible" id="editResponsible"
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Fecha límite</label>
                                                <input type="date" name="due_date" id="editDueDate" required
                                                    class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Estatus</label>
                                                <select name="status" id="editStatus"
                                                        class="text-gray-700 mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <option value="por_hacer">🟡 Por hacer</option>
                                                    <option value="haciendo">🔵 Haciendo</option>
                                                    <option value="hecho">🟢 Hecho</option>
                                                    <option value="cancelado">🔴 Cancelado</option>
                                                </select>
                                            </div>

                                            <div class="flex justify-end gap-3">
                                                <button type="button"
                                                        onclick="document.getElementById('modalEditar').classList.add('hidden')"
                                                        class="border px-4 py-2 rounded hover:bg-gray-100 text-gray-700">
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                        class="bg-indigo-600 text-white font-bold px-4 py-2 rounded hover:bg-indigo-700">
                                                    Guardar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
