@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Reportes de Trabajo</h1>

    <a href="{{ route('reportes-trabajo.create') }}" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded mb-4 inline-block">
        Nuevo Reporte
    </a>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Orden de Compra</th>
                <th class="px-4 py-2">Fecha Inicio</th>
                <th class="px-4 py-2">Fecha Fin</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportes as $reporte)
                <tr class="border-t border-gray-700">
                    <td class="px-4 py-2 text-white">{{ $reporte->ordenCompra->numero_oc ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-white">{{ $reporte->fecha_inicio }}</td>
                    <td class="px-4 py-2 text-white">{{ $reporte->fecha_fin }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('reportes-trabajo.show', $reporte) }}" class="text-blue-400">Ver</a> |
                        <a href="{{ route('reportes-trabajo.edit', $reporte) }}" class="text-yellow-400">Editar</a> |
                        <form action="{{ route('reportes-trabajo.destroy', $reporte) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este reporte?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $reportes->links() }}</div>
</div>
@endsection
