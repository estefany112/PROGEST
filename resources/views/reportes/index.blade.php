@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Reportes de Trabajo</h1>

        {{-- Asistente puede crear reportes --}}
        @role('asistente')
            <a href="{{ route('reportes-trabajo.create') }}" 
               class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Reporte
            </a>
        @endrole
    </div>

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

    {{-- Filtro por estado --}}
    <form method="GET" action="{{ route('reportes-trabajo.index') }}" class="mb-6 flex items-center space-x-3">
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

    {{-- Mensajes informativos según rol --}}
    @role('admin')
        <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
            <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Revisión</h4>
            <p class="text-blue-200">
                Aquí puedes revisar los reportes enviados por los asistentes.  
                Los reportes en estado "En Revisión" pueden ser aprobados o rechazados.
            </p>
        </div>
    @endrole

    @role('asistente')
        <div class="mb-6 p-4 bg-purple-900 border border-purple-700 rounded-lg">
            <h4 class="text-lg font-medium text-purple-100 mb-2">Gestión de Reportes</h4>
            <p class="text-purple-200">
                Aquí puedes crear, editar y enviar tus reportes a revisión.  
                Una vez enviados, el administrador los evaluará.
            </p>
        </div>
    @endrole

    {{-- Tabla --}}
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-900 rounded-lg">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Orden de Compra</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Archivo</th>
                            <th class="px-6 py-3 text-center text-sm text-gray-300 uppercase">Acciones</th>
                            @role('admin')
                                <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Creado por</th>
                            @endrole
                            @role('admin')
                                <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Revisado por</th>
                            @endrole
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Estado</th>
                        </tr>
                    </thead>

                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @forelse($reportes as $reporte)
                            <tr class="hover:bg-gray-800 transition">
                                <td class="px-6 py-4 text-white">
                                    {{ $reporte->ordenCompra->numero_oc ?? 'N/A' }}<br>
                                    <span class="text-sm text-gray-400">
                                        Cliente: {{ optional($reporte->ordenCompra->cotizacion)->cliente_nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($reporte->archivo_url)
                                        <a href="{{ $reporte->archivo_url }}" target="_blank" class="text-blue-400 hover:text-blue-300">Ver archivo</a>
                                    @else
                                        <span class="text-gray-400">Sin archivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex flex-wrap justify-center gap-2 items-center">
                                        
                                        {{-- Select de estado --}}
                                        <form action="{{ route('reportes-trabajo.cambiar-estado', $reporte) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                @role('admin')
                                                    <option value="aprobado" {{ $reporte->status == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                    <option value="rechazado" {{ $reporte->status == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                                @endrole

                                                @role('asistente')
                                                    <option value="borrador" {{ $reporte->status == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                    <option value="revision" {{ $reporte->status == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                                @endrole
                                            </select>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                Actualizar
                                            </button>
                                        </form>

                                        {{-- Ver --}}
                                        <a href="{{ route('reportes-trabajo.show', $reporte) }}" 
                                           class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                        {{-- Editar (solo si está en borrador) --}}
                                        @role('asistente')
                                            @if($reporte->status === 'borrador')
                                                <a href="{{ route('reportes-trabajo.edit', $reporte) }}" 
                                                   class="bg-yellow-600 hover:bg-yellow-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>
                                            @endif
                                        @endrole

                                        {{-- Eliminar (solo admin) --}}
                                        @role('admin')
                                            <form action="{{ route('reportes-trabajo.destroy', $reporte) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este reporte?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>

                                {{-- Creado por --}}
                                @role('admin')
                                    <td class="px-6 py-4 text-white">{{ $reporte->creadaPor->name ?? 'N/A' }}</td>
                                @endrole

                                {{-- Revisado por --}}
                                @role('admin')
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $reporte->revisadoPor->name ?? '—' }}
                                    </td>
                                @endrole

                                {{-- Estado --}}
                                <td class="px-6 py-4">
                                    <span class="
                                        @switch($reporte->status)
                                            @case('borrador') text-gray-400 @break
                                            @case('revision') text-yellow-400 @break
                                            @case('aprobado') text-green-400 @break
                                            @case('rechazado') text-red-400 @break
                                        @endswitch
                                        font-bold text-base">
                                        {{ ucfirst($reporte->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                    No hay reportes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $reportes->links() }}</div>
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
