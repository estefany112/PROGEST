@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle del Reporte de Trabajo</h1>

    <div class="bg-gray-900 p-6 rounded-lg shadow">
        <p class="mb-3"><strong class="text-gray-300">Orden de Compra:</strong> 
            {{ $reporte->ordenCompra->numero_oc ?? 'N/A' }}
        </p>

        <p class="mb-3"><strong class="text-gray-300">Fecha de Registro:</strong> 
            {{ $reporte->created_at->format('d/m/Y H:i') }}
        </p>

        @if($reporte->archivo)
            <p class="mb-3"><strong class="text-gray-300">Archivo:</strong></p>

            <!-- Vista previa según el tipo de archivo -->
            @php
                $extension = pathinfo($reporte->archivo, PATHINFO_EXTENSION);
            @endphp

            @if(in_array(strtolower($extension), ['jpg','jpeg','png','gif','webp']))
                <!-- Vista previa de imagen -->
                <img src="{{ Storage::url($reporte->archivo) }}" 
                     alt="Vista previa" 
                     class="max-w-full h-auto rounded border border-gray-700">
            
            @elseif(in_array(strtolower($extension), ['pdf']))
                <!-- Vista previa PDF -->
                <iframe src="{{ Storage::url($reporte->archivo) }}" 
                        class="w-full h-96 border border-gray-700 rounded"
                        frameborder="0"></iframe>
            
            @else
                <!-- Otros tipos de archivo -->
                <p class="text-gray-400">El archivo no se puede previsualizar.</p>
            @endif

            <!-- Botón de descarga -->
            <div class="mt-4">
                <a href="{{ Storage::url($reporte->archivo) }}" 
                   target="_blank"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                   Abrir / Descargar
                </a>
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
