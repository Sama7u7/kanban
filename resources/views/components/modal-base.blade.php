@props(['id', 'title', 'showVariable'])

<div id="{{ $id }}" x-show="{{ $showVariable }}" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4"
    style="display: none;" @keydown.escape.window="{{ $showVariable }} = false">

    {{-- Contenedor --}}
    <div class="bg-white dark:bg-[#161615] rounded-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl"
        @click.away="{{ $showVariable }} = false">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-800 pb-3">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $title }}</h2>
            <button @click="{{ $showVariable }} = false"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-3xl font-light leading-none">
                &times;
            </button>
        </div>

        {{-- Cuerpo del Modal (Aquí entra el formulario) --}}
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
