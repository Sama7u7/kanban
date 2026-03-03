<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Taskify') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->

            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-[#0a0a0a] text-[#EDEDEC] flex p-4 lg:p-8 items-center justify-center min-h-screen">
    @include('components.toasts')

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
        <main class="flex flex-col-reverse lg:flex-row w-full max-w-md lg:max-w-4xl shadow-2xl rounded-xl overflow-hidden border border-[#fffaed2d]">

            <div class="flex-1 p-8 lg:p-16 bg-[#161615] text-[#EDEDEC]">
                <h1 class="text-2xl font-bold mb-8 text-center lg:text-left">Inicio de sesión</h1>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <div class="mt-2">
                            <input id="email" type="email" name="email" required
                                class="block w-full rounded-md border-0 py-2.5 px-3 bg-white/5 text-white ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                            <div class="text-sm">
                                <a href="#" class="font-semibold text-secondary hover:text-indigo-300">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input id="password" type="password" name="password" required
                                class="block w-full rounded-md border-0 py-2.5 px-3 bg-white/5 text-white ring-1 ring-inset ring-white/10 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-secondary px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors">
                            Iniciar sesión
                        </button>
                    </div>
                </form>
            </div>


            <div class="bg-[#161615] w-full lg:w-1/2 flex items-center justify-center p-10 lg:p-16 min-h-55 lg:min-h-full border-b lg:border-b-0 lg:border-l border-[#fffaed2d]">
                <img src="{{ asset('images/logo-taskify.webp') }}" class="h-auto w-auto max-h-full max-w-full object-contain mx-auto" alt="Logo Taskify">
            </div>

        </main>
    </div>
</body>
</html>
