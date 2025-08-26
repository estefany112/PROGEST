
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Mis Cotizaciones</h1>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow">
        <table class="min-w-full bg-gray-900 rounded-lg">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-300">Folio</th>
                    <th class="px-4 py-2 text-left text-gray-300">Cliente</th>
                    <th class="px-4 py-2 text-left text-gray-300">Fecha</th>
                    <th class="px-4 py-2 text-left text-gray-300">Total</th>
                    <th class="px-4 py-2 text-left text-gray-300">Estado</th>
                    <th class="px-4 py-2 text-center text-gray-300">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizaciones as $cotizacion)
                    <tr class="border-b border-gray-700 hover:bg-gray-800">
                        <td class="px-4 py-2 text-white">{{ $cotizacion->folio }}</td>
                        <td class="px-4 py-2 text-white">{{ $cotizacion->cliente_nombre }}</td>
                        <td class="px-4 py-2 text-white">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-white">Q{{ number_format($cotizacion->total, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $cotizacion->estado_clase }} font-bold text-base">{{ $cotizacion->estado_texto }}</span>
                        </td>
                        <td class="px-4 py-2 text-center flex flex-col md:flex-row gap-2 justify-center items-center">
                            <a href="{{ route('cotizaciones.pdf', $cotizacion) }}" class="bg-green-600 hover:bg-green-800 text-white font-bold py-1 px-3 rounded shadow text-xs" target="_blank" download>PDF</a>
                            <a href="{{ route('cotizaciones.edit', $cotizacion) }}" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded shadow text-xs">Editar</a>
                           
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">No tienes cotizaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
