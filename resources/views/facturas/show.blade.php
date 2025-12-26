@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <h1 class="text-2xl font-bold text-gray-100 mb-6">
        Detalle de Factura
    </h1>

    <!-- Información de la factura -->
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-100 mb-4">
                Información de la Factura
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-400">Número de Factura</p>
                    <p class="text-lg text-gray-100">{{ $factura->numero_factura }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Cliente</p>
                    <p class="text-lg text-gray-100">
                        {{ $factura->cliente->nombre ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Orden de Compra</p>
                    <p class="text-lg text-gray-100">
                        {{ $factura->ordenCompra->numero_oc ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha de Emisión</p>
                    <p class="text-lg text-gray-100">
                        {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Monto Total</p>
                    <p class="text-lg font-bold text-blue-400">
                        Q{{ number_format($factura->monto_total, 2) }}
                    </p>
                </div>

                <!-- ESTADO -->
                <div>
                    <p class="text-sm font-medium text-gray-400">Estado</p>
                    @php
                        $estado = strtolower($factura->status ?? '');

                        if (str_contains($estado, 'revision')) {
                            $color = 'bg-yellow-600';
                            $label = 'En revisión';
                        } elseif ($estado === 'aprobado') {
                            $color = 'bg-green-600';
                            $label = 'Aprobado';
                        } elseif ($estado === 'rechazado') {
                            $color = 'bg-red-600';
                            $label = 'Rechazado';
                        } else {
                            $color = 'bg-gray-700';
                            $label = ucfirst($factura->status ?? 'Desconocido');
                        }
                    @endphp

                    <span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $color }}">
                        {{ $label }}
                    </span>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Archivo adjunto</p>

                    @if($factura->archivo_pdf_path)
                        <a href="{{ asset('storage/'.$factura->archivo_pdf_path) }}"
                           target="_blank"
                           class="text-blue-400 hover:text-blue-300">
                            Ver archivo PDF
                        </a>
                    @else
                        <p class="text-gray-300">No hay archivo adjunto</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ACCIONES -->
    <div class="flex justify-between items-center mt-6">
        <!-- Volver -->
        <a href="{{ route('facturas.index') }}"
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            ← Volver a Facturas
        </a>
    </div>
</div>
@endsection
