@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <h1 class="text-2xl font-bold text-gray-100 mb-6">
        Detalle de Pago
    </h1>

    <!-- Información del Pago -->
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-100 mb-4">Información del Pago</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-400">Orden de Compra</p>
                    <p class="text-lg text-gray-100">{{ $pago->factura->ordenCompra->numero_oc ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Cliente</p>
                    <p class="text-lg text-gray-100">{{ $pago->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Número de Factura</p>
                    <p class="text-lg text-gray-100">{{ $pago->factura->numero_factura ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Monto</p>
                    <p class="text-lg font-bold text-blue-400">
                        Q{{ number_format($pago->factura->monto_total ?? 0, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Contraseña de Pago</p>
                    <p class="text-lg text-gray-100">{{ $pago->contrasenaPago->codigo ?? 'Sin contraseña' }}</p>
                </div>

                <!-- Estado con colores -->
                <div>
                    <p class="text-sm font-medium text-gray-400">Estado</p>
                     @php
                        $estado = strtolower($pago->status ?? '');

                        if (str_contains($estado, 'pendiente')) {
                            $color = 'bg-yellow-600';
                            $label = 'Pendiente';
                        } elseif ($estado === 'pagada') {
                            $color = 'bg-green-600';
                            $label = 'Pagada';
                        } else {
                            $color = 'bg-gray-700';
                            $label = ucfirst($pago->status ?? 'Desconocido');
                        }
                    @endphp

                    <span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $color }}">
                        {{ $label }}
                    </span>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha de Pago</p>
                    <p class="text-lg text-gray-100">
                        {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'Pendiente' }}
                    </p>
                </div>

                <!-- Archivo adjunto -->
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Comprobante</p>
                    @if($pago->archivo)
                        <a href="{{ asset('storage/'.$pago->archivo) }}" 
                           target="_blank" 
                           class="text-blue-400 hover:text-blue-300">
                           Ver archivo
                        </a>
                    @else
                        <p class="text-gray-300">No hay archivo adjunto</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="flex justify-start mt-6">
        <a href="{{ route('pagos.index') }}" 
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
           ← Volver a Pagos
        </a>
    </div>
</div>
@endsection
