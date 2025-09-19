@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Órdenes de Compra</h1>

        <a href="{{ route('ordenes-compra.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <i class="fas fa-plus mr-2"></i> Nueva Orden de Compra
        </a>
    </div>

    <div class="pt-2 pb-10">
        @if(session('success'))
            <div id="alert-message" 
                 class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="alert-message" 
                 class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-900 rounded-lg">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Número OC</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Cotización</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Fecha</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-left text-sm text-gray-300 uppercase">Monto</th>
                                    <th class="px-6 py-3 border-b border-gray-700 text-center text-sm text-gray-300 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-900 divide-y divide-gray-800">
                                @forelse($ordenes as $orden)
                                    <tr class="hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-white">{{ $orden->numero_oc }}</td>
                                        <td class="px-6 py-4 text-white">
                                            {{ $orden->cotizacion->folio ?? 'N/A' }}
                                            <div class="text-sm text-gray-400">
                                                Cliente: {{ $orden->cotizacion->cliente_nombre ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-white">
                                            {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-white">
                                            Q{{ number_format($orden->monto_total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium">
                                            <a href="{{ route('ordenes-compra.show', $orden) }}" 
                                               class="text-blue-400 hover:text-blue-200">Ver</a> |
                                            <a href="{{ route('ordenes-compra.edit', $orden) }}" 
                                               class="text-yellow-400 hover:text-yellow-200">Editar</a> |
                                            <form action="{{ route('ordenes-compra.destroy', $orden) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Eliminar esta orden?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-200">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                            No hay órdenes de compra registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $ordenes->links() }}
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
