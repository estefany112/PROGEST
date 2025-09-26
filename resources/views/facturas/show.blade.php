@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle de Factura</h1>

    <div class="bg-gray-900 p-6 rounded-lg shadow">
        <p class="mb-3"><strong class="text-gray-300">Número:</strong> {{ $factura->numero_factura }}</p>
        <p class="mb-3"><strong class="text-gray-300">Cliente:</strong> {{ $factura->cliente->nombre ?? 'N/A' }}</p>
        <p class="mb-3"><strong class="text-gray-300">Orden de Compra:</strong> {{ $factura->ordenCompra->numero_oc ?? 'N/A' }}</p>
        <p class="mb-3"><strong class="text-gray-300">Fecha de Emisión:</strong> {{ $factura->fecha_emision }}</p>
        <p class="mb-3"><strong class="text-gray-300">Monto Total:</strong> Q{{ number_format($factura->monto_total, 2) }}</p>

        @if($factura->archivo_pdf_path)
            <p class="mb-3">
                <strong class="text-gray-300">Archivo:</strong> 
                <a href="{{ asset('storage/'.$factura->archivo_pdf_path) }}" target="_blank" class="text-blue-400 hover:underline">Ver documento adjunto</a>
            </p>
        @endif
    </div>

    <div class="mt-6 flex gap-4">
        <a href="{{ route('facturas.edit', $factura) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">Editar</a>
        <a href="{{ route('facturas.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">Volver</a>
    </div>
</div>
@endsection
