<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-200">
            Perfil de usuario
        </h2>
    </x-slot>

    <div class="py-10 bg-[#0f172a] min-h-screen">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 space-y-8">

            <!-- Información personal -->
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="border-b border-gray-700 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9 9 0 1118.364 4.561M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Información personal
                    </h3>
                    <span class="text-xs px-3 py-1 bg-green-600/20 text-green-400 rounded-full font-medium">Activo</span>
                </div>

                <div class="p-6 text-gray-300">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Seguridad -->
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="border-b border-gray-700 px-6 py-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c0-1.104.896-2 2-2s2 .896 2 2v2a2 2 0 01-2 2m0 0h-2a2 2 0 01-2-2v-2c0-1.104.896-2 2-2h2a2 2 0 012 2v2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-100">Seguridad y contraseña</h3>
                </div>

                <div class="p-6 text-gray-300">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Eliminar cuenta -->
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl shadow-md hover:shadow-lg transition">
                <div class="border-b border-gray-700 px-6 py-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-6 4v10m4-10v10m-2-10v10" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-100">Eliminar cuenta</h3>
                </div>

                <div class="p-6 text-gray-300">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Botón de volver -->
            <div class="flex justify-center pt-6">
                <a href="{{ route(Auth::user()->getRoleNames()->first() . '.dashboard') }}"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    ← Volver al panel principal
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
