@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Pagos</h1>

    <form method="POST" action="{{ route('pagos.store') }}" class="mb-6">
        @csrf
        <div class="flex gap-4">
            <select name="factura_id" class="border px-3 py-2 rounded bg-gray-800 text-white">
                @foreach($facturas as $factura)
                <option value="{{ $factura->id }}">Factura #{{ $factura->numero_factura }}</option>
                @endforeach
            </select>

            <select name="estado" class="border px-3 py-2 rounded bg-gray-800 text-white">
                <option value="pendiente">Pendiente</option>
                <option value="pagada">Pagada</option>
            </select>

            <input type="date" name="fecha_pago" class="border px-3 py-2 rounded bg-gray-800 text-white">
            <input type="text" name="metodo_pago" placeholder="Método" class="border px-3 py-2 rounded bg-gray-800 text-white">

            <button type="submit" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded">
                Guardar
            </button>
        </div>
    </form>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Factura</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Fecha</th>
                <th class="px-4 py-2">Método</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
            <tr class="border-t border-gray-700">
                <td class="px-4 py-2 text-white">#{{ $pago->factura->numero_factura }}</td>
                <td class="px-4 py-2 text-white">{{ ucfirst($pago->estado) }}</td>
                <td class="px-4 py-2 text-white">{{ $pago->fecha_pago ?? '-' }}</td>
                <td class="px-4 py-2 text-white">{{ $pago->metodo_pago ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
