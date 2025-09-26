@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Reporte de Trabajo</h1>

    @if ($errors->any())
        <div class="bg-red-800 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reportes-trabajo.update', $reporte->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Orden de compra -->
        <div>
            <label class="block mb-2 text-gray-300">Orden de Compra:</label>
            <select name="orden_compra_id" 
                    class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}" 
                        {{ $reporte->orden_compra_id == $orden->id ? 'selected' : '' }}>
                        {{ $orden->numero_oc }} - {{ $orden->cotizacion->cliente->nombre ?? '' }}
                    </option>
                @endforeach
            </select>
            @error('orden_compra_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Archivo -->
        <div>
            <label class="block mb-2 text-gray-300">Archivo actual:</label>
            @if($reporte->archivo_url)
                @php
                    $ext = pathinfo($reporte->archivo, PATHINFO_EXTENSION);
                @endphp

                @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                    <img src="{{ $reporte->archivo_url }}" class="max-w-sm border rounded mb-3">
                @elseif($ext === 'pdf')
                    <iframe src="{{ $reporte->archivo_url }}" 
                            class="w-full h-64 border rounded mb-3"></iframe>
                @else
                    <a href="{{ $reporte->archivo_url }}" target="_blank" class="text-blue-400 hover:underline">Ver archivo</a>
                @endif
            @else
                <p class="text-gray-400">No hay archivo adjunto.</p>
            @endif

            <label class="block mb-2 text-gray-300">Reemplazar archivo (opcional):</label>
            <input type="file" name="archivo" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
            @error('archivo') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                Actualizar Reporte
            </button>
            <a href="{{ route('reportes-trabajo.index') }}" class="ml-3 text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
</div>
@endsection
