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
                    <p class="text-lg text-gray-100">
                        {{ $orden->cotizacion->folio ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha</p>
                    <p class="text-lg text-gray-100">
                        {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Monto Total</p>
                    <p class="text-lg font-bold text-blue-400">
                        Q{{ number_format($orden->monto_total, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Estado actual</p>
                    @php
                        $estadoColors = [
                            'borrador' => 'bg-gray-600',
                            'revision' => 'bg-yellow-600',
                            'aprobado' => 'bg-green-600',
                            'rechazado' => 'bg-red-600'
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $estadoColors[$orden->status] ?? 'bg-gray-700' }}">
                        {{ ucfirst($orden->status) }}
                    </span>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Archivo adjunto</p>
                    @if($orden->archivo_oc_path)
                        <a href="{{ asset('storage/'.$orden->archivo_oc_path) }}" 
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
        <!-- Botón Volver -->
        <a href="{{ route('ordenes-compra.index') }}" 
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
            ← Volver a Órdenes de Compra
        </a>

        <!-- Acciones dinámicas -->
        <div class="flex space-x-3">
            @if(Auth::user()->role === 'asistente' && $orden->status === 'borrador')
                <!-- Editar -->
                <a href="{{ route('ordenes-compra.edit', $orden) }}" 
                   class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Editar
                </a>

                <!-- Enviar a revisión -->
                <form action="{{ route('ordenes-compra.enviarRevision', $orden->id) }}" method="POST" onsubmit="return confirm('¿Enviar esta orden a revisión?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Enviar a revisión
                    </button>
                </form>

                <!-- Eliminar -->
                <form action="{{ route('ordenes-compra.destroy', $orden) }}" method="POST" onsubmit="return confirm('¿Eliminar esta orden?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            @endif

            @if(Auth::user()->role === 'admin' && $orden->status === 'revision')
                <!-- Aprobar -->
                <form action="{{ route('ordenes-compra.cambiarEstado', $orden->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="aprobado">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Aprobar
                    </button>
                </form>

                <!-- Rechazar -->
                <form action="{{ route('ordenes-compra.cambiarEstado', $orden->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rechazado">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Rechazar
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
