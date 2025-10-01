@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Reportes de Trabajo</h1>

    <a href="{{ route('reportes-trabajo.create') }}" 
       class="bg-purple-600 hover:bg-purple-800 text-white px-4 py-2 rounded mb-4 inline-block">
        Nuevo Reporte
    </a>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Orden</th>
                <th class="px-4 py-2">Archivo</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportes as $reporte)
            <tr class="border-t border-gray-700">
                <td class="px-4 py-2 text-white">
                    {{ $reporte->ordenCompra->numero_oc ?? 'N/A' }} -
                     Cliente: {{ $reporte->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                </td>
                <td class="px-4 py-2 text-white">
                    @if($reporte->archivo_url)
                        <a href="{{ $reporte->archivo_url }}" target="_blank" class="text-blue-400 hover:underline">Ver archivo</a>
                    @else
                        <span class="text-gray-400">Sin archivo</span>
                    @endif
                </td>
                <td class="px-4 py-2 flex gap-3">
                    <!-- Ver -->
                    <a href="{{ route('reportes-trabajo.show', $reporte->id) }}" 
                       class="text-blue-400 hover:text-blue-600">Ver</a>

                    <!-- Editar -->
                    <a href="{{ route('reportes-trabajo.edit', $reporte->id) }}" 
                       class="text-yellow-400 hover:text-yellow-600">Editar</a>

                    <!-- Eliminar -->
                    <form action="{{ route('reportes-trabajo.destroy', $reporte->id) }}" 
                          method="POST" 
                          class="inline" 
                          onsubmit="return confirm('¿Eliminar este reporte?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-4 text-center text-gray-400">
                    No hay reportes registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">{{ $reportes->links() }}</div>
</div>
@endsection
