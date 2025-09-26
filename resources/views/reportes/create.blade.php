@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Nuevo Reporte de Trabajo</h1>

    <form action="{{ route('reportes-trabajo.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Orden de compra -->
        <div>
            <label class="block mb-2 text-gray-300">Orden de Compra:</label>
            <select name="orden_compra_id" class="w-full border bg-gray-900 text-white rounded px-3 py-2">
                <option value="">-- Selecciona una orden --</option>
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}">
                        {{ $orden->numero_oc }} - Cliente: {{ $orden->cotizacion->cliente->nombre ?? '' }}
                    </option>
                @endforeach
            </select>
            @error('orden_compra_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Archivo -->
        <div>
            <label class="block mb-2 text-gray-300">Archivo de Evidencia:</label>
            <input type="file" name="archivo" class="w-full border bg-gray-900 text-white rounded px-3 py-2">
            @error('archivo') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-green-600 px-4 py-2 text-white rounded hover:bg-green-700">Guardar</button>
        <a href="{{ route('reportes-trabajo.index') }}" class="ml-3 text-gray-400 hover:text-white">Cancelar</a>
    </form>
</div>
@endsection
