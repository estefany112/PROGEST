@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Órdenes de Compra</h1>

        {{-- Asistente puede crear reportes --}}
        @role('asistente')
            <a href="{{ route('ordenes-compra.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nueva Orden
            </a>
        @endrole
    </div>

    <div class="pt-2 pb-10">
        {{-- Mensajes flash --}}
        @if(session('success'))
            <div id="alert-message" class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="alert-message" class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded mb-4 duration-500">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filtros por Estado -->
        <form method="GET" action="{{ route('ordenes-compra.index') }}" class="mb-6 flex items-center space-x-3">
            <select name="status" class="px-3 py-2 border rounded-md bg-gray-800 text-white">
                <option value="">-- Todos los estados --</option>
                <option value="borrador" {{ request('status') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>En Revisión</option>
                <option value="aprobado" {{ request('status') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                <option value="rechazado" {{ request('status') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Filtrar
            </button>
        </form>

        {{-- Mensaje según rol --}}
        @role('admin')
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Revisión</h4>
                <p class="text-blue-200">
                    Aquí puedes revisar todas las órdenes de compra.  
                    Las que están en estado "En Revisión" requieren tu aprobación o rechazo.
                </p>
            </div>
        @endrole

        @role('asistente')
            <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
                <h4 class="text-lg font-medium text-blue-100 mb-2">Gestión de Órdenes</h4>
                <p class="text-blue-200">
                    Aquí puedes crear, editar y enviar tus órdenes a revisión.  
                    Una vez enviadas, el administrador las evaluará.
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
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">N° OC</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Cotización</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Fecha</th>
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Monto</th>
                                    @role('asistente')
                                        <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Revisado por</th>
                                    @endrole
                                    <th class="px-6 py-3 text-center text-sm text-gray-300 uppercase">Acciones</th>
                                    @role('admin')
                                        <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Creada por</th>
                                    @endrole
                                    <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Estado</th>
                                </tr>
                            </thead>

                            <tbody class="bg-gray-900 divide-y divide-gray-800">
                                @forelse($ordenes as $orden)
                                    <tr class="hover:bg-gray-800 transition">
                                        <td class="px-6 py-4 text-white">{{ $orden->numero_oc }}</td>
                                        <td class="px-6 py-4 text-white">
                                            {{ $orden->cotizacion->folio ?? 'N/A' }}
                                            <div class="text-sm text-gray-400">
                                                Cliente: {{ $orden->cotizacion->cliente_nombre ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-white">Q{{ number_format($orden->monto_total, 2) }}</td>

                                        {{-- Revisado por (solo asistente) --}}
                                        @role('asistente')
                                            <td class="px-6 py-4 text-gray-300">
                                                {{ $orden->revisadoPor->name ?? '—' }}
                                            </td>
                                        @endrole

                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex flex-wrap justify-center gap-2 items-center">

                                                {{-- Select de estado --}}
                                                <form action="{{ route('ordenes-compra.cambiarEstado', $orden->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    {{-- 🔹 Opciones adaptadas al rol --}}
                                                    @role('admin')
                                                        <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                            <option value="revision" {{ $orden->status == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                                            <option value="aprobado" {{ $orden->status == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                            <option value="rechazado" {{ $orden->status == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                                        </select>
                                                    @endrole

                                                    @role('asistente')
                                                        <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                            <option value="borrador" {{ $orden->status == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                            <option value="revision" {{ $orden->status == 'revision' ? 'selected' : '' }}>Enviar a Revisión</option>
                                                        </select>
                                                    @endrole

                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                        Actualizar
                                                    </button>
                                                </form>

                                                {{-- Botones --}}
                                                <a href="{{ route('ordenes-compra.show', $orden) }}" 
                                                   class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                                <a href="{{ route('ordenes-compra.edit', $orden) }}" 
                                                   class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>

                                                @role('admin')
                                                    <form action="{{ route('ordenes-compra.destroy', $orden) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('¿Seguro que deseas eliminar esta orden?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endrole
                                            </div>
                                        </td>

                                        @role('admin')
                                            <td class="px-6 py-4 text-white">{{ $orden->creadaPor->name ?? 'N/A' }}</td>
                                        @endrole

                                        <td class="px-6 py-4">
                                            <span class="
                                                @switch($orden->status)
                                                    @case('borrador') text-gray-400 @break
                                                    @case('revision') text-yellow-400 @break
                                                    @case('aprobado') text-green-400 @break
                                                    @case('rechazado') text-red-400 @break
                                                @endswitch
                                                font-bold text-base">
                                                {{ ucfirst($orden->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                            No hay órdenes de compra registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $ordenes->links() }}
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
