@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Pagos</h1>

    <a href="{{ route('pagos.create') }}" class="bg-purple-600 hover:bg-purple-800 text-white px-4 py-2 rounded mb-4 inline-block">
        Nuevo Pago
    </a>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Factura</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Fecha de Pago</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
            <tr class="border-t border-gray-700">
                <td class="px-4 py-2 text-white">
                    {{ $pago->factura->numero_factura ?? 'N/A' }} - 
                    Cliente: {{ $pago->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                </td>
                <td class="px-4 py-2 text-white">{{ ucfirst($pago->estado) }}</td>
                <td class="px-4 py-2 text-white">{{ $pago->fecha_pago ?? 'Pendiente' }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('pagos.show', $pago) }}" class="text-blue-400">Ver</a> |
                    <a href="{{ route('pagos.edit', $pago) }}" class="text-yellow-400">Editar</a> |
                    <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este pago?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $pagos->links() }}</div>
</div>
@endsection
