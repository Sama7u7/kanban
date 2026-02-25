<div id="modalVer" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg">

        {{-- Header del modal con botón X --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-700">Detalles de la actividad</h2>
            <button onclick="document.getElementById('modalVer').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Título</label>
            <p id="viewTitle" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[42px]"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Descripción</label>
            <p id="viewDescription" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[80px] whitespace-pre-wrap"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Responsable</label>
            <p id="viewResponsible" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[42px]"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Solicitante</label>
            <p id="viewRequester" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[42px]"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Fecha límite</label>
            <p id="viewDueDate" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[42px]"></p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700">Estatus</label>
            <p id="viewStatus" class="text-gray-700 mt-1 w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 min-h-[42px]"></p>
        </div>
    </div>
</div>
<script>
function abrirModalVer(id, title, description, responsible, requester, dueDate, status) {
    // Inyecta el texto en las etiquetas <p>
    document.getElementById('viewTitle').textContent = title;
    document.getElementById('viewDescription').textContent = description;
    document.getElementById('viewResponsible').textContent = responsible;
    document.getElementById('viewRequester').textContent = requester; // Asegúrate de mandar este parámetro
    document.getElementById('viewDueDate').textContent = dueDate;

    // Diccionario para dar formato visual al estatus
const estatusFormateado = {
    'por_hacer': '🟡 Por hacer',
    'haciendo': '🔵 Haciendo',
    'hecho': '🟢 Hecho',
    'cancelado': '🔴 Cancelado'
};

document.getElementById('viewStatus').textContent = estatusFormateado[status] || status;

    // Abre el modal
    document.getElementById('modalVer').classList.remove('hidden');
}
</script>
