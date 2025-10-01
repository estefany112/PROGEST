@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle de Pago</h1>

    <div class="bg-gray-900 p-6 rounded shadow text-gray-100">
        <p><strong>Orden de Compra:</strong> {{ $pago->factura->ordenCompra->numero_oc ?? 'N/A' }}</p>
        <p><strong>Cliente:</strong> {{ $pago->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Número Factura:</strong> {{ $pago->factura->numero_factura ?? 'N/A' }}</p>
        <p><strong>Monto:</strong> Q{{ number_format($pago->factura->monto_total ?? 0, 2) }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($pago->estado) }}</p>
        <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago ?? 'Pendiente' }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('pagos.index') }}" class="text-gray-400 hover:text-white">← Volver</a>
    </div>
</div>
@endsection
