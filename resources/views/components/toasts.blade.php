{{-- Toast de error --}}
@if ($errors->any())
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 bg-background rounded-lg shadow-lg border border-red-200" role="alert">
        <svg class="w-6 h-6 text-red-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M12 3a9 9 0 1 0 0 18A9 9 0 0 0 12 3Z"/>
        </svg>
        <div class="ms-2.5 text-sm text-red-600 font-bold ps-3.5 border-s border-red-200">
            {{ $errors->first() }}
        </div>
        <button onclick="document.getElementById('toast-error').classList.add('hidden')"
                class="ms-auto flex items-center justify-center text-gray-400 hover:text-gray-600 w-8 h-8 rounded">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <script>setTimeout(() => document.getElementById('toast-error')?.classList.add('hidden'), 4000);</script>
@endif

{{-- Toast de éxito --}}
@if (session('success'))
    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 bg-background rounded-lg shadow-lg border border-green-200" role="alert">
        <svg class="w-6 h-6 text-green-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <div class="ms-2.5 text-sm text-green-600 font-bold ps-3.5 border-s border-green-200">
            {{ session('success') }}
        </div>
        <button onclick="document.getElementById('toast-success').classList.add('hidden')"
                class="ms-auto flex items-center justify-center text-gray-400 hover:text-gray-600 w-8 h-8 rounded">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <script>setTimeout(() => document.getElementById('toast-success')?.classList.add('hidden'), 4000);</script>
@endif
