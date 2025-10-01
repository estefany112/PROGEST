@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Registrar Pago</h1>

    <form action="{{ route('pagos.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label class="block text-sm text-gray-300 mb-2">Factura</label>
            <select name="factura_id" class="w-full border border-gray-700 bg-gray-900 text-white rounded px-3 py-2">
                @foreach($facturas as $factura)
                    <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm text-gray-300 mb-2">Estado</label>
            <select name="estado" class="w-full border border-gray-700 bg-gray-900 text-white rounded px-3 py-2">
                <option value="pendiente">Pendiente</option>
                <option value="pagado">Pagado</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm text-gray-300 mb-2">Fecha de Pago</label>
            <input type="date" name="fecha_pago" class="w-full border border-gray-700 bg-gray-900 text-white rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            Guardar
        </button>
    </form>
</div>
@endsection
