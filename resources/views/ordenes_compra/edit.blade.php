@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pt-8 pb-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">
            Editar Orden de Compra - {{ $orden->numero_oc }}
        </h1>
    </div>

    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">

            {{-- ⚡ Mensajes de error globales --}}
            @if ($errors->any())
                <div class="bg-red-600 text-white p-3 mb-4 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ⚡ Mensaje de éxito --}}
            @if (session('success'))
                <div class="bg-green-600 text-white p-3 mb-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('ordenes-compra.update', $orden) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Selección de Cotización -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Cotización vinculada *</label>
                    <select name="cotizacion_id" required
                            class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($cotizaciones as $c)
                            <option value="{{ $c->id }}" {{ $orden->cotizacion_id == $c->id ? 'selected' : '' }}>
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
                    <input type="text" name="numero_oc" value="{{ old('numero_oc', $orden->numero_oc) }}" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('numero_oc')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Fecha *</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $orden->fecha) }}" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:ring-2 focus:ring-blue-500">
                    @error('fecha')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monto -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Monto Total *</label>
                    <input type="number" step="0.01" name="monto_total" value="{{ old('monto_total', $orden->monto_total) }}" required
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
                    @if($orden->archivo_oc_path)
                        <p class="text-sm text-gray-400 mt-2">
                            Archivo actual: 
                            <a href="{{ Storage::url($orden->archivo_oc_path) }}" target="_blank" class="text-blue-400 underline">
                                Ver archivo
                            </a>
                        </p>
                    @endif
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('ordenes-compra.index') }}"
                       class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Actualizar Orden
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
