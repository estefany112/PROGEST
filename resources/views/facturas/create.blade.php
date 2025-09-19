@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Nueva Factura</h1>

    @if ($errors->any())
        <div class="bg-red-800 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('facturas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Orden de compra -->
        <div>
            <label class="block mb-2 text-gray-300">Orden de Compra:</label>
            <select name="orden_compra_id" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                <option value="">-- Selecciona una orden --</option>
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}">
                        {{ $orden->numero_oc }} - {{ $orden->cotizacion->cliente_nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Número de factura -->
        <div>
            <label class="block mb-2 text-gray-300">Número Factura:</label>
            <input type="text" name="numero" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Fecha -->
        <div>
            <label class="block mb-2 text-gray-300">Fecha de Emisión:</label>
            <input type="date" name="fecha_emision" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Monto -->
        <div>
            <label class="block mb-2 text-gray-300">Monto:</label>
            <input type="number" step="0.01" name="monto" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Archivo PDF -->
        <div>
            <label class="block mb-2 text-gray-300">Archivo PDF:</label>
            <input type="file" name="archivo_pdf_path" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                Guardar Factura
            </button>
            <a href="{{ route('facturas.index') }}" class="ml-3 text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
</div>
@endsection
