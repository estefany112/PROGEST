@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Contraseñas de Pago</h1>

    <a href="{{ route('contrasenas.create') }}" class="bg-purple-600 hover:bg-purple-800 text-white px-4 py-2 rounded mb-4 inline-block">
        Nueva Contraseña
    </a>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Fecha Documento</th>
                <th class="px-4 py-2">Cliente</th>
                <th class="px-4 py-2">Documento Contraseña</th>
                <th class="px-4 py-2">Factura</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Fecha Proxima de Pago</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Archivo</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contrasenas as $contrasena)
            <tr class="border-t border-gray-700">

                 <!-- Fecha Documento -->
                <td class="px-4 py-2 text-white">
                    {{ $contrasena->fecha_documento ? \Carbon\Carbon::parse($contrasena->fecha_documento)->format('d/m/Y') : 'N/A' }}
                </td>

                <!-- Cliente -->
                <td class="px-4 py-2 text-white">
                    OC: {{ $contrasena->factura->ordenCompra->numero_oc ?? 'N/A' }} <br>
                    Cliente: {{ $contrasena->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                </td>

                <!-- Documento contraseña -->
                <td class="px-4 py-2 text-white">{{ $contrasena->codigo }}</td>

                <!-- Factura -->
                <td class="px-4 py-2 text-white">{{ $contrasena->factura->numero_factura ?? 'N/A' }}</td>

                <!-- Total -->
                <td class="px-4 py-2 text-white">
                    Q{{ number_format($contrasena->factura->monto_total,2) }}
                </td>

                <!-- Fecha Aproximada -->
                <td class="px-4 py-2 text-white">
                    {{ $contrasena->fecha_aprox ? \Carbon\Carbon::parse($contrasena->fecha_aprox)->format('d/m/Y') : 'N/A' }}
                </td>

                 <!-- Estado -->
                <td class="px-4 py-2 text-white">
                    <span class="px-2 py-1 rounded 
                        {{ $contrasena->estado === 'validada' ? 'bg-green-600 text-white' : 'bg-yellow-600 text-white' }}">
                        {{ ucfirst($contrasena->estado) }}
                    </span>
                </td>

                <!-- Archivo -->
                <td class="px-4 py-2 text-white">
                    @if($contrasena->archivo)
                        <a href="{{ asset('storage/'.$contrasena->archivo) }}" target="_blank" class="text-blue-400 hover:underline">Ver archivo</a>
                    @else
                        Sin archivo
                    @endif
                </td>

                <!-- Acciones -->
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('contrasenas.show', $contrasena) }}" class="text-blue-400">Ver</a> |
                    <a href="{{ route('contrasenas.edit', $contrasena) }}" class="text-yellow-400">Editar</a> |
                    <form action="{{ route('contrasenas.destroy', $contrasena) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta contraseña?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $contrasenas->links() }}</div>
</div>
@endsection
