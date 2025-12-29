@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Facturas</h1>

        {{-- Asistente puede crear facturas --}}
        @role('asistente')
            <a href="{{ route('facturas.create') }}" 
               class="bg-purple-600 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Nueva Factura
            </a>
        @endrole
    </div>

    {{-- Mensajes Flash --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: @json(session('success')),
        timer: 1800,
        showConfirmButton: false,
        background: '#111827',
        color: '#e5e7eb'
    });
    </script>
    @endif

    @if(session('error'))
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: @json(session('error')),
        background: '#111827',
        color: '#e5e7eb'
    });
    </script>
    @endif

    {{-- Filtro por estado --}}
    <form method="GET" action="{{ route('facturas.index') }}" class="mb-6 flex items-center space-x-3">
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
    
    {{-- Panel informativo según rol --}}
    @role('admin')
        <div class="mb-6 p-4 bg-blue-900 border border-blue-700 rounded-lg">
            <h4 class="text-lg font-medium text-blue-100 mb-2">Panel de Revisión</h4>
            <p class="text-blue-200">
                Aquí puedes revisar las facturas enviadas por los asistentes.  
                Las que estén “En revisión” pueden ser aprobadas o rechazadas.
            </p>
        </div>
    @endrole

    @role('asistente')
        <div class="mb-6 p-4 bg-purple-900 border border-purple-700 rounded-lg">
            <h4 class="text-lg font-medium text-purple-100 mb-2">Gestión de Facturas</h4>
            <p class="text-purple-200">
                Aquí puedes crear, editar y enviar tus facturas a revisión.  
                Una vez enviadas, el administrador las validará.
            </p>
        </div>
    @endrole

    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-900 rounded-lg">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Orden de Compra</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">N° Factura</th>
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
                        @forelse($facturas as $factura)
                            <tr class="hover:bg-gray-800 transition">
                                <td class="px-6 py-4 text-white">
                                    {{ $factura->ordenCompra->numero_oc ?? 'N/A' }}<br>
                                    <span class="text-sm text-gray-400">
                                        Cliente: {{ $factura->ordenCompra->cotizacion->cliente_nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-white">{{ $factura->numero_factura }}</td>
                                <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-white">Q{{ number_format($factura->monto_total, 2) }}</td>

                                {{-- Revisado por (solo asistente) --}}
                                @role('asistente')
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $factura->revisadoPor->name ?? '—' }}
                                    </td>
                                @endrole

                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex flex-wrap justify-center gap-2 items-center">

                                        {{-- Select de estado --}}
                                        <form action="{{ route('facturas.cambiarEstado', $factura->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            @role('admin')
                                                <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                    <option value="revision" {{ $factura->status == 'revision' ? 'selected' : '' }}>En Revisión</option>
                                                    <option value="aprobado" {{ $factura->status == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                                                    <option value="rechazado" {{ $factura->status == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                                </select>
                                            @endrole

                                            @role('asistente')
                                                <select name="status" class="px-2 py-1 border border-gray-700 bg-gray-900 text-gray-100 rounded-md text-xs">
                                                    <option value="borrador" {{ $factura->status == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                                    <option value="revision" {{ $factura->status == 'revision' ? 'selected' : '' }}>Enviar a Revisión</option>
                                                </select>
                                            @endrole

                                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                Actualizar
                                            </button>
                                        </form>

                                        {{-- Botones --}}
                                        <a href="{{ route('facturas.show', $factura) }}" 
                                           class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-1 px-3 rounded text-xs">Ver</a>

                                        <a href="{{ route('facturas.edit', $factura) }}" 
                                           class="bg-yellow-600 hover:bg-yellow-800 text-white font-bold py-1 px-3 rounded text-xs">Editar</a>

                                         @role('admin')
                                        <form id="form-delete-{{ $factura->id }}" 
                                            action="{{ route('facturas.destroy', $factura) }}" 
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    onclick="confirmarEliminacion({{ $factura->id }}, '{{ $factura->numero_factura ?? 'F-' . $factura->id }}')"

                                                    class="bg-red-600 hover:bg-red-800 text-white font-bold py-1 px-3 rounded text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>

                                @role('admin')
                                    <td class="px-6 py-4 text-white">{{ $factura->creadaPor->name ?? 'N/A' }}</td>
                                @endrole

                                <td class="px-6 py-4">
                                    <span class="
                                        @switch($factura->status)
                                            @case('borrador') text-gray-400 @break
                                            @case('revision') text-yellow-400 @break
                                            @case('aprobado') text-green-400 @break
                                            @case('rechazado') text-red-400 @break
                                        @endswitch
                                        font-bold text-base">
                                        {{ ucfirst($factura->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                    No hay facturas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $facturas->links() }}</div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmarEliminacion(id, numero_factura) {
    Swal.fire({
      title: '¿Eliminar factura?',
      html: `<p>La factura <strong>${numero_factura}</strong> será eliminado permanentemente.</p>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e3342f',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      background: '#111827',
      color: '#e5e7eb',
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`form-delete-${id}`).submit();
      }
    });
  }
</script>

@endsection
