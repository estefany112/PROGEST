@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <h1 class="text-2xl font-bold text-gray-100 mb-6">
        Detalle de Orden de Compra
    </h1>

    <!-- Información de la orden -->
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-100 mb-4">Información de la Orden</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-400">Número OC</p>
                    <p class="text-lg text-gray-100">{{ $orden->numero_oc }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Cotización</p>
                    <p class="text-lg text-gray-100">{{ $orden->cotizacion->folio ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha</p>
                    <p class="text-lg text-gray-100">{{ $orden->fecha }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Monto Total</p>
                    <p class="text-lg font-bold text-blue-400">
                        Q{{ number_format($orden->monto_total, 2) }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Archivo</p>
                    @if($orden->archivo_oc_path)
                        <a href="{{ asset('storage/'.$orden->archivo_oc_path) }}" 
                           target="_blank" 
                           class="text-blue-400 hover:text-blue-300">
                           Ver archivo adjunto
                        </a>
                    @else
                        <p class="text-gray-300">No adjunto</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="flex justify-end">
        <a href="{{ route('ordenes-compra.index') }}" 
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            Volver a Órdenes de Compra
        </a>
    </div>
</div>
@endsection
