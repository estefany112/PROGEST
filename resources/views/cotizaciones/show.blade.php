@extends('layouts.app')


@section('content')
    <hr>

            <!-- Información del cliente -->
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-blue-100 mb-4">Información del Cliente</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Nombre</p>
                            <p class="text-lg text-gray-100">{{ $cotizacion->cliente_nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">NIT</p>
                            <p class="text-lg text-gray-100">{{ $cotizacion->cliente_nit }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-400">Dirección</p>
                            <p class="text-lg text-gray-100">{{ $cotizacion->cliente_direccion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items de la cotización -->
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-blue-100 mb-4">Items de la Cotización</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-900">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-xs leading-4 font-medium text-gray-300 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-xs leading-4 font-medium text-gray-300 uppercase tracking-wider">
                                        Unidad
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-xs leading-4 font-medium text-gray-300 uppercase tracking-wider">
                                        Descripción
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-xs leading-4 font-medium text-gray-300 uppercase tracking-wider">
                                        Precio Unitario
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-xs leading-4 font-medium text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-900">
                                @foreach($cotizacion->items as $item)
                                    <tr class="hover:bg-gray-800">
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-800">
                                            <div class="text-sm leading-5 text-gray-100">{{ $item->cantidad }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-800">
                                            <div class="text-sm leading-5 text-gray-100">{{ $item->unidad_medida }}</div>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-800">
                                            <div class="text-sm leading-5 text-gray-100">{{ $item->descripcion }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-800">
                                            <div class="text-sm leading-5 text-gray-100">Q{{ number_format($item->precio_unitario, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-800">
                                            <div class="text-sm leading-5 font-medium text-blue-200">Q{{ number_format($item->total, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Totales -->
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-blue-100 mb-4">Totales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-400">Subtotal</p>
                            <p class="text-2xl font-bold text-gray-100">Q{{ number_format($cotizacion->subtotal, 2) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-400">IVA (19%)</p>
                            <p class="text-2xl font-bold text-gray-100">Q{{ number_format($cotizacion->iva, 2) }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-400">Total</p>
                            <p class="text-3xl font-bold text-blue-400">Q{{ number_format($cotizacion->total, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mb-4">
                <a href="{{ route('cotizaciones') }}" class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
                    Ver todas las cotizaciones
                </a>
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
@endsection
