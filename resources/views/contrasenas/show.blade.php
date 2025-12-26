@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <h1 class="text-2xl font-bold text-gray-100 mb-6">
        Detalle de Contraseña
    </h1>

    <!-- Información de la contraseña -->
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-100 mb-4">
                Información del Documento
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-400">Documento Contraseña</p>
                    <p class="text-lg text-gray-100">
                        {{ $contrasena->codigo }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Factura</p>
                    <p class="text-lg text-gray-100">
                        {{ $contrasena->factura->numero_factura ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Cliente</p>
                    <p class="text-lg text-gray-100">
                        {{ $contrasena->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha del Documento</p>
                    <p class="text-lg text-gray-100">
                        {{ $contrasena->fecha_documento
                            ? \Carbon\Carbon::parse($contrasena->fecha_documento)->format('d/m/Y')
                            : 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Monto Total</p>
                    <p class="text-lg font-bold text-blue-400">
                        Q{{ number_format($contrasena->factura->monto_total ?? 0, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha Aproximada</p>
                    <p class="text-lg text-gray-100">
                        {{ $contrasena->fecha_aprox
                            ? \Carbon\Carbon::parse($contrasena->fecha_aprox)->format('d/m/Y')
                            : 'N/A' }}
                    </p>
                </div>

                <!-- ESTADO (CORREGIDO DEFINITIVAMENTE) -->
                <div>
                    <div>
                    <p class="text-sm font-medium text-gray-400">Estado</p>
                    @php
                        $estado = strtolower($contrasena->status ?? '');

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
                            $label = ucfirst($contrasena->status ?? 'Desconocido');
                        }
                    @endphp

                    <span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $color }}">
                        {{ $label }}
                    </span>
                </div>
            </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Archivo adjunto</p>
                    @if($contrasena->archivo)
                        <a href="{{ asset('storage/'.$contrasena->archivo) }}"
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

    <!-- ACCIONES -->
    <div class="flex justify-between items-center mt-6">
        <a href="{{ route('contrasenas.index') }}"
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            ← Volver a Contraseñas
        </a>
    </div>
</div>
@endsection
