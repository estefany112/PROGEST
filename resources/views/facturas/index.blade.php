@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Facturas</h1>

    <a href="{{ route('facturas.create') }}" class="bg-purple-600 hover:bg-purple-800 text-white px-4 py-2 rounded mb-4 inline-block">
        Nueva Factura
    </a>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Orden de Compra</th>
                <th class="px-4 py-2">Número</th>
                <th class="px-4 py-2">Fecha</th>
                <th class="px-4 py-2">Monto</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $factura)
                <tr class="border-t border-gray-700">
                    <td class="px-4 py-2 text-white">{{ $factura->ordenCompra->numero_oc ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-white">{{ $factura->numero }}</td>
                    <td class="px-4 py-2 text-white">{{ $factura->fecha_emision }}</td>
                    <td class="px-4 py-2 text-white">Q{{ number_format($factura->monto, 2) }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('facturas.show', $factura) }}" class="text-blue-400">Ver</a> |
                        <a href="{{ route('facturas.edit', $factura) }}" class="text-yellow-400">Editar</a> |
                        <form action="{{ route('facturas.destroy', $factura) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta factura?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $facturas->links() }}</div>
</div>
@endsection
