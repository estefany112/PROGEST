<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cotizaciones') }}
            </h2>
            @if(Auth::user()->tipo === 'asistente')
                <a href="{{ route('cotizaciones.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Nueva Cotización
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(Auth::user()->tipo === 'admin')
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-lg font-medium text-blue-900 mb-2">Panel de Revisión</h4>
                            <p class="text-blue-700">
                                Aquí puedes ver todas las cotizaciones. Las que están en estado "En Revisión" 
                                requieren tu aprobación o rechazo.
                            </p>
                        </div>
                    @elseif(Auth::user()->tipo === 'asistente')
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="text-lg font-medium text-green-900 mb-2">Gestión de Cotizaciones</h4>
                            <p class="text-green-700">
                                Aquí puedes crear, editar y enviar tus cotizaciones a revisión. 
                                Una vez enviadas, el administrador las revisará.
                            </p>
                        </div>
                    @endif

                    @if(Auth::user()->tipo === 'admin')
                        <div class="overflow-x-auto">
                            <!-- Tabla completa solo para admin -->
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Folio</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Total</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-center text-sm leading-4 tracking-wider">Acciones</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Creada por</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @forelse($cotizaciones as $cotizacion)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->folio }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->cliente_nombre }}<div class="text-sm leading-5 text-gray-500">NIT: {{ $cotizacion->cliente_nit }}</div></td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">Q{{ number_format($cotizacion->total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm font-medium">
                                                <form action="{{ route('cotizaciones.cambiar-estado', $cotizacion) }}" method="POST" class="flex flex-row flex-wrap gap-2 justify-center items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="estado" class="px-2 py-1 border rounded-md focus:ring-2 focus:ring-blue-500 text-xs">
                                                        <option value="borrador" {{ $cotizacion->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                        <option value="en_revision" {{ $cotizacion->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                                        <option value="aprobada" {{ $cotizacion->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                                        <option value="rechazada" {{ $cotizacion->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                                    </select>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">Actualizar</button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->creadaPor->name }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><span class="{{ $cotizacion->estado_clase }} font-bold text-base mb-1">{{ $cotizacion->estado_texto }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay cotizaciones disponibles.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @elseif(Auth::user()->tipo === 'asistente')
                        <div class="overflow-x-auto">
                            <!-- Tabla simple solo para asistente -->
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Folio</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Total</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 tracking-wider">Estado</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-center text-sm leading-4 tracking-wider">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @forelse($cotizaciones as $cotizacion)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->folio }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->cliente_nombre }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">Q{{ number_format($cotizacion->total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><span class="{{ $cotizacion->estado_clase }} font-bold text-base mb-1">{{ $cotizacion->estado_texto }}</span></td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center">
                                                <a href="{{ route('cotizaciones.pdf', $cotizacion) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-1 px-3 rounded shadow text-xs" target="_blank" download>Descargar PDF</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay cotizaciones disponibles.</td>
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
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
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
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
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
</x-app-layout> 