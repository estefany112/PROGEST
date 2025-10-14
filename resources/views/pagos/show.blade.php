@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Detalle de Pago</h1>

    <div class="bg-gray-900 p-6 rounded shadow text-gray-100 space-y-2">
        <p><strong>Orden de Compra:</strong> {{ $pago->factura->ordenCompra->numero_oc ?? 'N/A' }}</p>
        <p><strong>Cliente:</strong> {{ $pago->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Número de Factura:</strong> {{ $pago->factura->numero_factura ?? 'N/A' }}</p>
        <p><strong>Monto:</strong> Q{{ number_format($pago->factura->monto_total ?? 0, 2) }}</p>

        {{-- Contraseña de pago (opcional) --}}
        <p><strong>Contraseña de Pago:</strong> 
            {{ $pago->contrasenaPago->codigo ?? 'Sin contraseña' }}
        </p>

        <p><strong>Estado:</strong> {{ ucfirst($pago->status) }}</p>
        <p><strong>Fecha de Pago:</strong> 
            {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'Pendiente' }}
        </p>

        @if($pago->archivo)
            <p><strong>Comprobante:</strong> 
                <a href="{{ asset('storage/'.$pago->archivo) }}" target="_blank" class="text-blue-400 hover:underline">
                    Ver archivo
                </a>
            </p>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('pagos.index') }}" class="text-gray-400 hover:text-white">← Volver</a>
    </div>
</div>
@endsection
