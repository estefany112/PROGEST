@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Nuevo Reporte de Trabajo</h1>

    <div class="bg-gray-900 p-6 rounded-lg shadow border border-gray-800">
        <form action="{{ route('reportes-trabajo.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Orden de Compra -->
            <div class="mb-6">
                <label for="orden_compra_id" class="block text-gray-300 font-medium mb-2">Orden de Compra *</label>
                <select name="orden_compra_id" id="orden_compra_id" required
                        class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-white rounded-md focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Selecciona una orden aprobada --</option>
                    @foreach($ordenes as $orden)
                        <option value="{{ $orden->id }}" {{ old('orden_compra_id') == $orden->id ? 'selected' : '' }}>
                            OC: {{ $orden->numero_oc }} - Cliente: {{ $orden->cotizacion->cliente_nombre ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('orden_compra_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Archivo -->
            <div class="mb-6">
                <label for="archivo" class="block text-gray-300 font-medium mb-2">Archivo del Reporte *</label>
                <input type="file" name="archivo" id="archivo" required
                       class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-white rounded-md">
                @error('archivo')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-2">Formatos permitidos: PDF, JPG, PNG. Tamaño máximo: 5 MB</p>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('reportes-trabajo.index') }}"
                   class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-md">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-semibold">
                    Guardar Reporte
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
