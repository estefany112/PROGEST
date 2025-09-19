@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Cotizaciones</h1>
        @if(Auth::user()->tipo === 'asistente')
            <a href="{{ route('cotizaciones.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i>Nueva Cotización
            </a>
        @endif
    </div>

    <div class="pt-2 pb-10">
        @if(session('success'))
            <div id="alert-message" class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="alert-message" class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filtros por Estado -->
        <form method="GET" action="{{ route('cotizaciones.index') }}" class="mb-6 flex items-center space-x-3">
            <select name="estado" class="px-3 py-2 border rounded-md bg-gray-800 text-white">
                <option value="">-- Todos los estados --</option>
                <option value="borrador" {{ request('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                <option value="aprobada" {{ request('estado') == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Filtrar
            </button>
        </form>

        @if(Auth::user()->tipo === 'admin')
            <!-- Mensaje admin -->
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Revisión</h4>
                <p class="text-blue-200">
                    Aquí puedes ver todas las cotizaciones. Las que están en estado "En Revisión" 
                    requieren tu aprobación o rechazo.
                </p>
            </div>
        @elseif(Auth::user()->tipo === 'asistente')
            <!-- Mensaje asistente -->
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Gestión de Cotizaciones</h4>
                <p class="text-blue-200">
                    Aquí puedes crear, editar y enviar tus cotizaciones a revisión. 
                    Una vez enviadas, el administrador las revisará.
                </p>
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-900 rounded-lg">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Folio</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Cliente</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Fecha</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Total</th>
                                    <th class="px-6 py-3 text-center text-sm text-gray-300 uppercase">Acciones</th>
                                    @if(Auth::user()->tipo === 'admin')
                                        <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Creada por</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-900 divide-y divide-gray-800">
                                @forelse($cotizaciones as $cotizacion)
                                    <tr class="hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-white">{{ $cotizacion->folio }}</td>
                                        <td class="px-6 py-4 text-white">{{ $cotizacion->cliente_nombre }}</td>
                                        <td class="px-6 py-4 text-white">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-white">Q{{ number_format($cotizacion->total, 2) }}</td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex flex-wrap justify-center gap-2 items-center">
                                                {{-- Select de estado --}}
                                                <form action="{{ route('cotizaciones.cambiar-estado', $cotizacion) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="estado" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                        @if(Auth::user()->tipo === 'admin')
                                                            <option value="aprobada" {{ $cotizacion->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                            <option value="rechazada" {{ $cotizacion->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                                        @elseif(Auth::user()->tipo === 'asistente')
                                                            <option value="borrador" {{ $cotizacion->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                            <option value="en_revision" {{ $cotizacion->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                                        @endif
                                                    </select>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                        Actualizar
                                                    </button>
                                                </form>

                                                {{-- Botones CRUD --}}
                                                <a href="{{ route('cotizaciones.pdf', $cotizacion) }}" 
                                                   class="bg-green-600 hover:bg-green-800 text-white font-bold py-1 px-3 rounded text-xs"
                                                   target="_blank" download>PDF</a>

                                                <a href="{{ route('cotizaciones.edit', $cotizacion) }}" 
                                                   class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>

                                                <a href="{{ route('cotizaciones.show', $cotizacion) }}" 
                                                   class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                                <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar esta cotización?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        @if(Auth::user()->tipo === 'admin')
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->creadaPor->name }}</td>
                                        @endif

                                        <td class="px-6 py-4">
                                            <span class="{{ $cotizacion->estado_clase }} font-bold text-base text-white">
                                                {{ $cotizacion->estado_texto }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                            No hay cotizaciones disponibles.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $cotizaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const alert = document.getElementById("alert-message");
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        }
    });
</script>
@endsection
