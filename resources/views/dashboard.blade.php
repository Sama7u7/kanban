@extends('layouts.test')

@section('content')
    <h1 class="text-4xl  font-mono pb-8">Tareas</h1>

    <div class="grid grid-cols-3 gap-4 border rounded-xl border-gray-300 px-6 py-4">

        <!-- Botón de guardar inicia -->
        <div class="mt-8 text-center col-span-3">
            <button class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Crear un tablero
        </button>
        </div>
        <!-- Botón de guardar termina -->
@endsection
