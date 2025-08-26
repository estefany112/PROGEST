<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cotización') }} - {{ $cotizacion->folio }}
            </h2>
            <div class="flex space-x-2">
                @if(Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Dashboard</a>
                @elseif(Route::has('home'))
                    <a href="{{ route('home') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Inicio</a>
                @endif
                @if(Route::has('profile.show'))
                    <a href="{{ route('profile.show') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver mi perfil</a>
                @endif
                <a href="{{ url()->previous() }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Regresar</a>
                @if(Auth::user()->tipo === 'asistente' && $cotizacion->estado === 'borrador' && $cotizacion->creada_por === Auth::id())
                    <a href="{{ route('cotizaciones.edit', $cotizacion) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Editar
                    </a>
                @endif
                
@if(isset($cotizacion) && $cotizacion->id)
    <a href="{{ route('cotizaciones.pdf', ['cotizacion' => $cotizacion->id]) }}" target="_blank"
       class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        <i class="fas fa-file-pdf mr-2"></i>PDF
    </a>
@endif
                
                <a href="{{ route('cotizaciones.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Información general -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Folio</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $cotizacion->folio }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado</p>
                            <span class="{{ $cotizacion->estado_clase }} text-sm">
                                {{ $cotizacion->estado_texto }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha de Emisión</p>
                            <p class="text-lg text-gray-900">
                                @if($cotizacion->fecha_emision)
                                    {{ $cotizacion->fecha_emision instanceof \Carbon\Carbon ? $cotizacion->fecha_emision->format('d/m/Y') : $cotizacion->fecha_emision }}
                                @else
                                    <span class="text-gray-400">No definida</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Creada por</p>
                            <p class="text-lg text-gray-900">
                                @if($cotizacion->creadaPor)
                                    {{ $cotizacion->creadaPor->name }}
                                @else
                                    <span class="text-gray-400">No definido</span>
                                @endif
                            </p>
                        </div>
                        @if($cotizacion->revisadaPor)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Revisada por</p>
                                <p class="text-lg text-gray-900">{{ $cotizacion->revisadaPor->name }}</p>
                            </div>
                        @endif
                        @if($cotizacion->comentario_rechazo)
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500">Comentario de Rechazo</p>
                                <p class="text-lg text-red-600 bg-red-50 p-3 rounded">{{ $cotizacion->comentario_rechazo }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información del cliente -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Cliente</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                            <p class="text-lg text-gray-900">{{ $cotizacion->cliente_nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">NIT</p>
                            <p class="text-lg text-gray-900">{{ $cotizacion->cliente_nit }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Dirección</p>
                            <p class="text-lg text-gray-900">{{ $cotizacion->cliente_direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items de la cotización -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Items de la Cotización</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Unidad
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Precio Unitario
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($cotizacion->items as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">{{ $item->cantidad }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">{{ $item->unidad_medida }}</div>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">{{ $item->descripcion }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                                         <div class="text-sm leading-5 text-gray-900">Q{{ number_format($item->precio_unitario, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                                         <div class="text-sm leading-5 font-medium text-gray-900">Q{{ number_format($item->total, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Totales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Totales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Subtotal</p>
                                                         <p class="text-2xl font-bold text-gray-900">Q{{ number_format($cotizacion->subtotal, 2) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">IVA (19%)</p>
                                                         <p class="text-2xl font-bold text-gray-900">Q{{ number_format($cotizacion->iva, 2) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-500">Total</p>
                                                         <p class="text-3xl font-bold text-blue-600">Q{{ number_format($cotizacion->total, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones según el estado -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones</h3>
                    <div class="flex flex-wrap gap-4">

                        @if(Auth::user()->tipo === 'asistente' && $cotizacion->estado === 'borrador' && $cotizacion->creada_por === Auth::id())
                            <form action="{{ route('cotizaciones.enviar-revision', $cotizacion) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('¿Enviar cotización a revisión?')">
                                    <i class="fas fa-paper-plane mr-2"></i>Enviar a Revisión
                                </button>
                            </form>
                        @endif

                        @if(Auth::user()->tipo === 'admin' && isset($cotizacion) && $cotizacion->id)
                            <form action="{{ route('cotizaciones.cambiar-estado', $cotizacion) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label for="estado" class="text-sm font-medium text-gray-700 mr-2">Cambiar estado:</label>
                                <select id="estado" name="estado" class="px-2 py-1 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option value="borrador" {{ $cotizacion->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                    <option value="en_revision" {{ $cotizacion->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                    <option value="aprobada" {{ $cotizacion->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                    <option value="rechazada" {{ $cotizacion->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                            </form>
                        @endif

                        @if($cotizacion->estado === 'aprobada')
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                <i class="fas fa-info-circle mr-2"></i>
                                Esta cotización fue <b>aprobada</b> por un administrador y está lista para continuar el proceso.
                            </div>
                        @elseif($cotizacion->estado === 'rechazada')
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <i class="fas fa-info-circle mr-2"></i>
                                Esta cotización fue <b>rechazada</b> por un administrador. Consulta el comentario de rechazo si existe.
                            </div>
                        @elseif($cotizacion->estado === 'en_revision')
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                                <i class="fas fa-info-circle mr-2"></i>
                                Esta cotización está <b>en revisión</b> por el administrador.
                            </div>
                        @endif
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
                @if(isset($cotizacion) && $cotizacion->id)
                    <form action="{{ route('cotizaciones.rechazar', $cotizacion) }}" method="POST">
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
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Rechazar
                            </button>
                        </div>
                    </form>
                @endif
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
        function mostrarModalRechazo() {
            document.getElementById('modalRechazo').classList.remove('hidden');
        }

        function cerrarModalRechazo() {
            document.getElementById('modalRechazo').classList.add('hidden');
        }
    </script>
</x-app-layout>
