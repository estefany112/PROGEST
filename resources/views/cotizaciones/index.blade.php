@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Cotizaciones</h1>
        @if(Auth::user()->tipo === 'asistente')
            <a href="{{ route('cotizaciones.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i>Nueva Cotización
            </a>
        @endif
    </div>

    <div class="pt-2 pb-10">
        @if(session('success'))
            <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if(Auth::user()->tipo === 'admin')
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Revisión</h4>
                <p class="text-blue-200">
                    Aquí puedes ver todas las cotizaciones. Las que están en estado "En Revisión" 
                    requieren tu aprobación o rechazo.
                </p>
            </div>
        @elseif(Auth::user()->tipo === 'asistente')
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

                    @if(Auth::user()->tipo === 'admin')
                        <div class="overflow-x-auto">
                            <!-- Tabla completa solo para admin -->
                            <table class="min-w-full bg-gray-900 rounded-lg">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Folio</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Cliente</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Fecha</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Total</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-center text-sm text-gray-300 uppercase">Acciones</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Creada por</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-900 divide-y divide-gray-800">
                                    @forelse($cotizaciones as $cotizacion)
                                        <tr class="hover:bg-gray-800 transition">
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->folio }}</td>
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->cliente_nombre }}<div class="text-sm leading-5 text-gray-400">NIT: {{ $cotizacion->cliente_nit }}</div></td>
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-white">Q{{ number_format($cotizacion->total, 2) }}</td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <div class="flex flex-row flex-wrap gap-2 justify-center items-center">
                                                    <form action="{{ route('cotizaciones.cambiar-estado', $cotizacion) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="estado" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500 text-xs">
                                                            <option value="borrador" {{ $cotizacion->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                            <option value="en_revision" {{ $cotizacion->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                                            <option value="aprobada" {{ $cotizacion->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                            <option value="rechazada" {{ $cotizacion->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                                        </select>
                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">Actualizar</button>
                                                    </form>
                                                    <form action="{{ route('cotizaciones.destroy', $cotizacion) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta cotización?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->creadaPor->name }}</td>
                                            <td class="px-6 py-4"><span class="{{ $cotizacion->estado_clase }} font-bold text-base mb-1 text-white">{{ $cotizacion->estado_texto }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 bg-gray-900">No hay cotizaciones disponibles.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @elseif(Auth::user()->tipo === 'asistente')
                        <div class="overflow-x-auto">
                            <!-- Tabla simple solo para asistente -->
                            <table class="min-w-full bg-gray-900 rounded-lg">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Folio</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Cliente</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Fecha</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Total</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Estado</th>
                                        <th class="px-6 py-3 border-b border-gray-700 text-center text-sm text-gray-300 uppercase">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-900 divide-y divide-gray-800">
                                    @forelse($cotizaciones as $cotizacion)
                                        <tr class="hover:bg-gray-800 transition">
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->folio }}</td>
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->cliente_nombre }}</td>
                                            <td class="px-6 py-4 text-white">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-white">Q{{ number_format($cotizacion->total, 2) }}</td>
                                            <td class="px-6 py-4"><span class="{{ $cotizacion->estado_clase }} font-bold text-base mb-1 text-white">{{ $cotizacion->estado_texto }}</span></td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('cotizaciones.pdf', $cotizacion) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-1 px-3 rounded shadow text-xs" target="_blank" download>Descargar PDF</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 bg-gray-900">No hay cotizaciones disponibles.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-4">
                        {{ $cotizaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar cotización -->
    <div id="modalRechazo" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-gray-900 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-medium mb-4">Rechazar Cotización</h3>
                <form id="formRechazo" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="comentario_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                            Comentario (opcional)
                        </label>
                        <textarea id="comentario_rechazo" name="comentario_rechazo" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="cerrarModalRechazo()" 
                                class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarModalRechazo(cotizacionId) {
            document.getElementById('formRechazo').action = `/cotizaciones/${cotizacionId}/rechazar`;
            document.getElementById('modalRechazo').classList.remove('hidden');
        }

        function cerrarModalRechazo() {
            document.getElementById('modalRechazo').classList.add('hidden');
        }
    </script>
@endsection