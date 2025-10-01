@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 bg-gray-900 rounded">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle de Contraseña</h1>

    <!-- Encabezado -->
    <p class="text-white mb-2">Guatemala, {{ $contrasena->fecha_documento ? \Carbon\Carbon::parse($contrasena->fecha_documento)->format('d/m/Y') : 'N/A' }}</p>

    <!-- Datos de la factura -->
    <p class="text-white mb-2"><strong>Cliente:</strong> 
        {{ $contrasena->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
    </p>
    <p class="text-white mb-2"><strong>Documento Contraseña:</strong> {{ $contrasena->codigo }}</p>
    <p class="text-white mb-2"><strong>Factura:</strong> {{ $contrasena->factura->numero_factura ?? 'N/A' }}</p>
    <p class="text-white mb-2"><strong>Total:</strong> 
        Q{{ number_format($contrasena->factura->monto_total,2) }}
    </p>

    <!-- Fechas -->
    <p class="text-white mb-2"><strong>Fecha Aproximada:</strong> 
        {{ $contrasena->fecha_aprox ? \Carbon\Carbon::parse($contrasena->fecha_aprox)->format('d/m/Y') : 'N/A' }}
    </p>

    <!-- Archivo -->
    @if($contrasena->archivo)
        <p class="mt-4">
            <strong class="text-white">Archivo adjunto:</strong>
            <a href="{{ asset('storage/'.$contrasena->archivo) }}" 
               target="_blank" 
               class="text-blue-400 hover:underline">
                Ver / Descargar
            </a>
        </p>
    @else
        <p class="text-white mt-4"><strong>Archivo adjunto:</strong> No se proporcionó</p>
    @endif

    <!-- Estado -->
    <p class="text-white mt-4"><strong>Estado:</strong> {{ ucfirst($contrasena->estado) }}</p>
</div>
@endsection
