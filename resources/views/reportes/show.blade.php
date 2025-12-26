@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <h1 class="text-2xl font-bold text-gray-100 mb-6">
        Detalle del Reporte de Trabajo
    </h1>

    <!-- Información del Reporte -->
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-800">
        <div class="p-6">
            <h3 class="text-lg font-medium text-blue-100 mb-4">Información del Reporte</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-400">Orden de Compra</p>
                    <p class="text-lg text-gray-100">{{ $reporte->ordenCompra->numero_oc ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Fecha de Registro</p>
                    <p class="text-lg text-gray-100">{{ $reporte->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-400">Creado por</p>
                    <p class="text-lg text-gray-100">{{ $reporte->creadaPor->name ?? 'N/A' }}</p>
                </div>

                @if($reporte->revisadoPor)
                <div>
                    <p class="text-sm font-medium text-gray-400">Revisado por</p>
                    <p class="text-lg text-gray-100">{{ $reporte->revisadoPor->name }}</p>
                </div>
                @endif

                <!-- ESTADO con colores -->
                <div>
                    <p class="text-sm font-medium text-gray-400">Estado actual</p>
                    @php
                        use Illuminate\Support\Str;

                        $estadoNormalizado = Str::of($reporte->status)->lower()->ascii()->replace(' ', '_')->__toString();

                        $estadoColors = [
                            'borrador' => 'bg-gray-600',
                            'revision' => 'bg-yellow-600',
                            'aprobado' => 'bg-green-600',
                            'rechazado' => 'bg-red-600',
                        ];

                        $estadoLabels = [
                            'borrador' => 'Borrador',
                            'revision' => 'En revisión',
                            'aprobado' => 'Aprobado',
                            'rechazado' => 'Rechazado',
                        ];
                    @endphp

                    <span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $estadoColors[$estadoNormalizado] ?? 'bg-gray-700' }}">
                        {{ $estadoLabels[$estadoNormalizado] ?? ucfirst($reporte->status) }}
                    </span>
                </div>

                <!-- Archivo adjunto -->
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-400">Archivo adjunto</p>
                    @if($reporte->archivo)
                        @php $extension = pathinfo($reporte->archivo, PATHINFO_EXTENSION); @endphp

                        @if(in_array(strtolower($extension), ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ Storage::url($reporte->archivo) }}" 
                                 alt="Vista previa" 
                                 class="max-w-full h-auto rounded border border-gray-700 mb-4">
                        
                        @elseif(strtolower($extension) === 'pdf')
                            <iframe src="{{ Storage::url($reporte->archivo) }}" 
                                    class="w-full h-96 border border-gray-700 rounded mb-4"
                                    frameborder="0"></iframe>
                        @else
                            <p class="text-gray-300">El archivo no se puede previsualizar.</p>
                        @endif

                    @else
                        <p class="text-gray-300">No se adjuntó archivo en este reporte.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón volver -->
    <div class="flex justify-start mt-6">
        <a href="{{ route('reportes-trabajo.index') }}" 
           class="inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
           ← Volver a Reportes
        </a>
    </div>
</div>
@endsection
