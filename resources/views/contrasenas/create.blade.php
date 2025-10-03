@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Registrar Contraseña de Pago</h1>

    <form action="{{ route('contrasenas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

       <!-- Fecha del documento -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-200 mb-2">Fecha del Documento *</label>
            <input type="date" name="fecha_documento" value="{{ old('fecha_documento', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md">
        </div>

        <!-- Factura -->
        <div>
            <label class="block mb-2 text-gray-300">Factura:</label>
            <select name="factura_id" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                <option value="">-- Selecciona una factura --</option>
                @foreach($facturas as $factura)
                    <option value="{{ $factura->id }}">
                        OC: {{ $factura->ordenCompra->numero_oc ?? 'N/A' }} - 
                        Cliente: {{ $factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                        - Total: Q{{ number_format($factura->monto_total,2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Código -->
        <div>
            <label class="block mb-2 text-gray-300">Documento Contraseña:</label>
            <input type="text" name="codigo" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Fecha Aprox -->
        <div>
            <label class="block mb-2 text-gray-300">Fecha aproximada:</label>
            <input type="date" name="fecha_aprox" 
                value="{{ old('fecha_aprox', $contrasena->fecha_aprox ?? '') }}"
                class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Archivo -->
        <div>
            <label class="block mb-2 text-gray-300">Archivo de la contraseña (opcional):</label>
            <input type="file" name="archivo" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Guardar</button>
    </form>
</div>
@endsection
