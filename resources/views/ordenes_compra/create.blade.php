@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pt-8 pb-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Nueva Orden de Compra</h1>
        <div class="flex space-x-2">
            <a href="{{ route('ordenes-compra.index') }}" 
               class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                Volver al listado
            </a>
        </div>
    </div>

    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <form action="{{ route('ordenes-compra.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Selección de Cotización -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Cotización aprobada *</label>
                    <select name="cotizacion_id" required
                            class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Selecciona una cotización --</option>
                        @foreach($cotizaciones as $c)
                            <option value="{{ $c->id }}" {{ old('cotizacion_id') == $c->id ? 'selected' : '' }}>
                                Folio: {{ $c->folio }} - Cliente: {{ $c->cliente_nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cotizacion_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número OC -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Número OC *</label>
                    <input type="text" name="numero_oc" value="{{ old('numero_oc') }}" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('numero_oc')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Fecha *</label>
                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('fecha')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monto -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Monto Total *</label>
                    <input type="number" step="0.01" name="monto_total" value="{{ old('monto_total') }}" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('monto_total')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Archivo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Archivo OC (opcional)</label>
                    <input type="file" name="archivo_oc_path" 
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md">
                    @error('archivo_oc_path')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('ordenes-compra.index') }}"
                       class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Guardar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
