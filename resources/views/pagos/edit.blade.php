@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Pago</h1>

    @if ($errors->any())
        <div class="bg-red-800 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pagos.update', $pago->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Factura -->
        <div>
            <label class="block mb-2 text-gray-300">Factura:</label>
            <select name="factura_id" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                @foreach($facturas as $factura)
                    <option value="{{ $factura->id }}" {{ $pago->factura_id == $factura->id ? 'selected' : '' }}>
                        {{ $factura->numero_factura }} — Q{{ number_format($factura->monto_total, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="block mb-2 text-gray-300">Estado:</label>
            <select name="estado" class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
                <option value="pendiente" {{ $pago->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="pagado" {{ $pago->estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
            </select>
        </div>

        <!-- Fecha de pago -->
        <div>
            <label class="block mb-2 text-gray-300">Fecha de Pago:</label>
            <input type="date" name="fecha_pago" value="{{ old('fecha_pago', $pago->fecha_pago) }}"
                   class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <!-- Botones -->
        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                Actualizar Pago
            </button>
            <a href="{{ route('pagos.index') }}" class="ml-3 text-gray-400 hover:text-white">Cancelar</a>
        </div>
    </form>
</div>
@endsection
