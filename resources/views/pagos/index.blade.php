@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Pagos</h1>

        {{-- Asistente puede crear pagos --}}
        @role('asistente')
            <a href="{{ route('pagos.create') }}" 
               class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Pago
            </a>
        @endrole
    </div>

    <div class="pt-2 pb-10">
        {{-- Mensajes flash --}}
        @if(session('success'))
            <div id="alert-message" class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="alert-message" class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Panel informativo --}}
        @role('admin')
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Pagos</h4>
                <p class="text-blue-200">
                    Aquí puedes revisar los pagos registrados y cambiar su estado a "Pagada" cuando se confirme la transacción.
                </p>
            </div>
        @endrole

        @role('asistente')
            <div class="mb-6 p-4 bg-purple-900 border border-purple-700 rounded-lg">
                <h4 class="text-lg font-medium text-purple-100 mb-2">Gestión de Pagos</h4>
                <p class="text-purple-200">
                    Aquí puedes registrar y gestionar tus pagos.  
                    El administrador confirmará cuando el pago esté completado.
                </p>
            </div>
        @endrole

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-900 rounded-lg">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Factura</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Cliente</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Fecha de Pago</th>
                                    {{-- Solo asistente ve quién revisó --}}
                                    @role('asistente')
                                        <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Revisado por</th>
                                    @endrole
                                    <th class="px-6 py-3 text-center text-sm text-gray-300 uppercase">Acciones</th>
                                    @role('admin')
                                        <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Registrado por</th>
                                    @endrole
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Estado</th>
                                </tr>
                            </thead>

                            <tbody class="bg-gray-900 divide-y divide-gray-800">
                                @forelse($pagos as $pago)
                                    <tr class="hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-white">
                                            {{ $pago->factura->numero_factura ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-white">
                                            {{ $pago->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-white">
                                            {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'Pendiente' }}
                                        </td>

                                        {{-- Revisado por (solo asistente) --}}
                                        @role('asistente')
                                            <td class="px-6 py-4 text-gray-300">
                                                {{ $pago->revisadoPor->name ?? '—' }}
                                            </td>
                                        @endrole

                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex flex-wrap justify-center gap-2 items-center">
                                                {{-- Select de estado --}}
                                                <form action="{{ route('pagos.cambiarEstado', $pago->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    @role('admin')
                                                        <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                            <option value="pendiente" {{ $pago->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="pagada" {{ $pago->status == 'pagada' ? 'selected' : '' }}>Pagada</option>
                                                        </select>

                                                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                            Actualizar
                                                        </button>
                                                    @endrole
                                                </form>

                                                {{-- Botones --}}
                                                <a href="{{ route('pagos.show', $pago) }}" 
                                                   class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                                @role('asistente')
                                                    <a href="{{ route('pagos.edit', $pago) }}" 
                                                       class="bg-yellow-600 hover:bg-yellow-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>
                                                @endrole

                                                @role('admin')
                                                    <form action="{{ route('pagos.destroy', $pago) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('¿Seguro que deseas eliminar este pago?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endrole
                                            </div>
                                        </td>

                                        {{-- Creado por (solo admin) --}}
                                        @role('admin')
                                            <td class="px-6 py-4 text-white">{{ $pago->creadaPor->name ?? 'N/A' }}</td>
                                        @endrole

                                        {{-- Estado --}}
                                        <td class="px-6 py-4">
                                            <span class="
                                                @switch($pago->status)
                                                    @case('pendiente') text-yellow-400 @break
                                                    @case('pagada') text-green-400 @break
                                                @endswitch
                                                font-bold text-base">
                                                {{ ucfirst($pago->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                            No hay pagos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pagos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const alert = document.getElementById("alert-message");
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        }
    });
</script>
@endsection
