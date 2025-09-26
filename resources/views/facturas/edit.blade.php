@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Factura</h1>

    @if ($errors->any())
        <div class="bg-red-800 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('facturas.update', $factura->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Orden de compra -->
        <div>
            <label class="block mb-2 text-gray-300">Orden de Compra:</label>
            <select name="orden_compra_id" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                @foreach($ordenes as $orden)
                    <option value="{{ $orden->id }}" {{ $factura->orden_compra_id == $orden->id ? 'selected' : '' }}>
                        {{ $orden->numero_oc }} - {{ $orden->cotizacion->cliente->nombre ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Número de factura -->
        <div>
            <label class="block mb-2 text-gray-300">Número Factura:</label>
            <input type="text" name="numero_factura" value="{{ old('numero_factura', $factura->numero_factura) }}"
                   class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Fecha -->
        <div>
            <label class="block mb-2 text-gray-300">Fecha de Emisión:</label>
            <input type="date" name="fecha_emision" value="{{ old('fecha_emision', $factura->fecha_emision) }}"
                   class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Monto -->
        <div>
            <label class="block mb-2 text-gray-300">Monto Total:</label>
            <input type="number" step="0.01" name="monto_total" value="{{ old('monto_total', $factura->monto_total) }}"
                   class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Archivo adjunto -->
        <div>
            <label class="block mb-2 text-gray-300">Archivo adjunto:</label>

            @if($factura->archivo_pdf_path)
                @php
                    $ext = pathinfo($factura->archivo_pdf_path, PATHINFO_EXTENSION);
                @endphp

                <div class="mb-3">
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                        <!-- Vista previa de imagen -->
                        <img src="{{ Storage::url($factura->archivo_pdf_path) }}" 
                            alt="Archivo actual" 
                            class="max-w-xs rounded border border-gray-700">

                    @elseif(strtolower($ext) === 'pdf')
                        <!-- Vista previa de PDF -->
                        <iframe src="{{ Storage::url($factura->archivo_pdf_path) }}" 
                                class="w-full h-64 border rounded"></iframe>

                    @else
                        <!-- Otros archivos -->
                        <a href="{{ Storage::url($factura->archivo_pdf_path) }}" 
                        target="_blank" 
                        class="text-blue-400 hover:underline">
                        Ver archivo actual
                        </a>
                    @endif
                </div>
            @endif

            <!-- Subir nuevo archivo -->
            <input type="file" name="archivo_pdf_path" 
                class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
            @error('archivo_pdf_path')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>       

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                Actualizar Factura
            </button>
            <a href="{{ route('facturas.index') }}" class="ml-3 text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
</div>
@endsection
