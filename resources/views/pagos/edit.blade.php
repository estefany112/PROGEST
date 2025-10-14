@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Pago</h1>

    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pagos.update', $pago->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-gray-900 p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <!-- Factura -->
        <div>
            <label for="factura_id" class="block text-gray-300 mb-2">Factura</label>
            <select name="factura_id" id="factura_id" class="bg-gray-800 text-white rounded px-3 py-2 w-full" required>
                <option value="">-- Selecciona una factura --</option>
                @foreach($facturas as $factura)
                    <option value="{{ $factura->id }}" {{ $pago->factura_id == $factura->id ? 'selected' : '' }}>
                        {{ $factura->numero_factura }} — Cliente: {{ $factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Contraseña de Pago (opcional) -->
        <div>
            <label for="contrasena_id" class="block text-gray-300 mb-2">Contraseña de Pago (opcional)</label>
            <select name="contrasena_id" id="contrasena_id" class="bg-gray-800 text-white rounded px-3 py-2 w-full">
                <option value="">— Sin contraseña —</option>
                @foreach($contrasenas as $c)
                    <option value="{{ $c->id }}" {{ $pago->contrasena_id == $c->id ? 'selected' : '' }}>
                        {{ $c->codigo }} — Factura: {{ $c->factura->numero_factura ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Fecha de Pago -->
        <div>
            <label for="fecha_pago" class="block text-gray-300 mb-2">Fecha de Pago</label>
            <input type="date" name="fecha_pago" id="fecha_pago" value="{{ old('fecha_pago', $pago->fecha_pago) }}" class="bg-gray-800 text-white rounded px-3 py-2 w-full" required>
        </div>

        <!-- Archivo -->
        <div>
            <label for="archivo" class="block text-gray-300 mb-2">Comprobante (PDF o imagen)</label>
            <input type="file" name="archivo" id="archivo" accept=".pdf,.jpg,.png" class="bg-gray-800 text-white rounded px-3 py-2 w-full">

            @if($pago->archivo)
                <p class="text-sm text-gray-400 mt-2">
                    Archivo actual: 
                    <a href="{{ asset('storage/'.$pago->archivo) }}" target="_blank" class="text-blue-400 hover:underline">
                        Ver comprobante
                    </a>
                </p>
            @endif
        </div>

        <!-- Estado (solo lectura) -->
        <div>
            <label class="block text-gray-300 mb-2">Estado actual</label>
            <p class="text-white font-semibold">
                {{ ucfirst($pago->status) }}
            </p>
        </div>

        <!-- Botones -->
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('pagos.index') }}" class="text-gray-400 hover:text-white">← Volver</a>

            <button type="submit" class="bg-purple-600 hover:bg-purple-800 text-white px-6 py-2 rounded font-semibold">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
