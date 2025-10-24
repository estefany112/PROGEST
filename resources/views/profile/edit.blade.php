@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h2 class="text-2xl font-bold text-gray-100 mb-8">Perfil de usuario</h2>

    <!-- Información personal -->
    <div class="bg-[#1e293b] border border-gray-700 rounded-xl shadow-md hover:shadow-lg transition mb-8">
        <div class="border-b border-gray-700 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM4 20c0-2.21 3.582-4 8-4s8 1.79 8 4" />
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
    <div class="bg-[#1e293b] border border-gray-700 rounded-xl shadow-md hover:shadow-lg transition mb-8">
        <div class="border-b border-gray-700 px-6 py-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10zM9 12l2 2 4-4" />
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
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-6 4v10m4-10v10m4-10v10M4 7h16" />
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
@endsection
