@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Contraseñas de Pago</h1>

        {{-- Asistente puede crear nuevas contraseñas --}}
        @role('asistente')
            <a href="{{ route('contrasenas.create') }}" 
               class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nueva Contraseña
            </a>
        @endrole
    </div>

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

    {{-- Filtro por estado --}}
    <form method="GET" action="{{ route('contrasenas.index') }}" class="mb-6 flex items-center space-x-3">
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
                Aquí puedes revisar las contraseñas enviadas por los asistentes.  
                Las que están en estado "En Revisión" pueden ser aprobadas o rechazadas.
            </p>
        </div>
    @endrole

    @role('asistente')
        <div class="mb-6 p-4 bg-purple-900 border border-purple-700 rounded-lg">
            <h4 class="text-lg font-medium text-purple-100 mb-2">Gestión de Contraseñas</h4>
            <p class="text-purple-200">
                Aquí puedes crear, editar y enviar tus contraseñas a revisión.  
                Una vez enviadas, el administrador las evaluará.
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
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Fecha Documento</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Factura</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Total</th>
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
                        @forelse($contrasenas as $contrasena)
                            <tr class="hover:bg-gray-800 transition">
                                <td class="px-6 py-4 text-white">
                                    {{ $contrasena->fecha_documento ? \Carbon\Carbon::parse($contrasena->fecha_documento)->format('d/m/Y') : 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-white">
                                    OC: {{ $contrasena->factura->ordenCompra->numero_oc ?? 'N/A' }}<br>
                                    <span class="text-sm text-gray-400">
                                        Cliente: {{ $contrasena->factura->ordenCompra->cotizacion->cliente->nombre ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-white">
                                    {{ $contrasena->factura->numero_factura ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-white">
                                    Q{{ number_format($contrasena->factura->monto_total ?? 0, 2) }}
                                </td>

                                {{-- Revisado por (solo asistente) --}}
                                @role('asistente')
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $contrasena->revisadoPor->name ?? '—' }}
                                    </td>
                                @endrole

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex flex-wrap justify-center gap-2 items-center">
                                        {{-- Select de estado --}}
                                        <form action="{{ route('contrasenas.cambiarEstado', $contrasena->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            @role('admin')
                                                <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                    <option value="revision" {{ $contrasena->status == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                                    <option value="aprobado" {{ $contrasena->status == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                    <option value="rechazado" {{ $contrasena->status == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                                </select>
                                            @endrole

                                            @role('asistente')
                                                <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                    <option value="borrador" {{ $contrasena->status == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                    <option value="revision" {{ $contrasena->status == 'revision' ? 'selected' : '' }}>Enviar a Revisión</option>
                                                </select>
                                            @endrole

                                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                Actualizar
                                            </button>
                                        </form>

                                        {{-- Ver --}}
                                        <a href="{{ route('contrasenas.show', $contrasena) }}" 
                                           class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                        {{-- Editar --}}
                                        @role('asistente')
                                            @if($contrasena->status === 'borrador')
                                                <a href="{{ route('contrasenas.edit', $contrasena) }}" 
                                                   class="bg-yellow-600 hover:bg-yellow-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>
                                            @endif
                                        @endrole

                                        {{-- Eliminar --}}
                                        @role('admin')
                                            <form action="{{ route('contrasenas.destroy', $contrasena) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta contraseña?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endrole
                                    </div>
                                </td>

                                {{-- Creada por (solo admin) --}}
                                @role('admin')
                                    <td class="px-6 py-4 text-white">{{ $contrasena->creadaPor->name ?? 'N/A' }}</td>
                                @endrole

                                {{-- Estado --}}
                                <td class="px-6 py-4">
                                    <span class="
                                        @switch($contrasena->status)
                                            @case('borrador') text-gray-400 @break
                                            @case('revision') text-yellow-400 @break
                                            @case('aprobado') text-green-400 @break
                                            @case('rechazado') text-red-400 @break
                                        @endswitch
                                        font-bold text-base">
                                        {{ ucfirst($contrasena->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                    No hay contraseñas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $contrasenas->links() }}</div>
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
