@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle del Reporte de Trabajo</h1>

    <div class="bg-gray-900 p-6 rounded-lg shadow space-y-4 border border-gray-800">
        <!-- Orden y fecha -->
        <p><strong class="text-gray-300">Orden de Compra:</strong> 
            <span class="text-gray-100">{{ $reporte->ordenCompra->numero_oc ?? 'N/A' }}</span>
        </p>

        <p><strong class="text-gray-300">Fecha de Registro:</strong> 
            <span class="text-gray-100">{{ $reporte->created_at->format('d/m/Y H:i') }}</span>
        </p>

        <!-- Estado -->
        <p><strong class="text-gray-300">Estado:</strong>
            <span class="@switch($reporte->status)
                            @case('borrador') text-gray-400 @break
                            @case('revision') text-yellow-400 @break
                            @case('aprobado') text-green-400 @break
                            @case('rechazado') text-red-400 @break
                        @endswitch font-semibold">
                {{ ucfirst($reporte->status) }}
            </span>
        </p>

        <!-- Creado por -->
        <p><strong class="text-gray-300">Creado por:</strong>
            <span class="text-gray-100">{{ $reporte->creadaPor->name ?? 'N/A' }}</span>
        </p>

        <!-- Revisado por (solo si existe) -->
        @if($reporte->revisadoPor)
            <p><strong class="text-gray-300">Revisado por:</strong>
                <span class="text-gray-100">{{ $reporte->revisadoPor->name }}</span>
            </p>
        @endif

        <!-- Archivo -->
        @if($reporte->archivo)
            <div class="mt-4">
                <p class="mb-2"><strong class="text-gray-300">Archivo adjunto:</strong></p>

                @php
                    $extension = pathinfo($reporte->archivo, PATHINFO_EXTENSION);
                @endphp

                @if(in_array(strtolower($extension), ['jpg','jpeg','png','gif','webp']))
                    <img src="{{ Storage::url($reporte->archivo) }}" 
                         alt="Vista previa" 
                         class="max-w-full h-auto rounded border border-gray-700">
                
                @elseif(strtolower($extension) === 'pdf')
                    <iframe src="{{ Storage::url($reporte->archivo) }}" 
                            class="w-full h-96 border border-gray-700 rounded"
                            frameborder="0"></iframe>
                @else
                    <p class="text-gray-400">El archivo no se puede previsualizar.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ Storage::url($reporte->archivo) }}" 
                       target="_blank"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                       Abrir / Descargar
                    </a>
                </div>
            </div>
        @else
            <p class="text-gray-400">No se adjuntó archivo en este reporte.</p>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('reportes-trabajo.index') }}" 
           class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">
           Volver
        </a>
    </div>
</div>
@endsection
