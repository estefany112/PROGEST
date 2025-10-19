@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Bitácora de Actividades</h1>
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

    {{-- 🔹 Filtros --}}
    <form method="GET" action="{{ route('bitacoras.index') }}" class="mb-6 flex flex-wrap items-center space-x-3">
        <select name="accion" class="px-3 py-2 border rounded-md bg-gray-800 text-white">
            <option value="">-- Todas las acciones --</option>
            <option value="Creación" {{ request('accion') == 'Creación' ? 'selected' : '' }}>Creación</option>
            <option value="Actualización" {{ request('accion') == 'Actualización' ? 'selected' : '' }}>Actualización</option>
            <option value="Eliminación" {{ request('accion') == 'Eliminación' ? 'selected' : '' }}>Eliminación</option>
        </select>

        <select name="modulo" class="px-3 py-2 border rounded-md bg-gray-800 text-white">
            <option value="">-- Todos los módulos --</option>
            <option value="Usuarios" {{ request('modulo') == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
            <option value="Clientes" {{ request('modulo') == 'Clientes' ? 'selected' : '' }}>Clientes</option>
            <option value="Cotización" {{ request('modulo') == 'Cotización' ? 'selected' : '' }}>Cotización</option>
            <option value="Orden de Compra" {{ request('modulo') == 'Orden de Compra' ? 'selected' : '' }}>Orden de Compra</option>
            <option value="Factura" {{ request('modulo') == 'Factura' ? 'selected' : '' }}>Factura</option>
            <option value="Reporte de Trabajo" {{ request('modulo') == 'Reporte de Trabajo' ? 'selected' : '' }}>Reporte de Trabajo</option>
            <option value="Contraseña de pago" {{ request('modulo') == 'Contraseña de pago' ? 'selected' : '' }}>Contraseña de pago</option>
            <option value="Pagos" {{ request('modulo') == 'Pagos' ? 'selected' : '' }}>Pagos</option>
        </select>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Filtrar
        </button>
    </form>

    {{-- 🔹 Tabla de registros --}}
    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-900 rounded-lg">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Acción</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Detalle</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Módulo</th>
                            <th class="px-6 py-3 text-left text-sm text-gray-300 uppercase">Fecha</th>
                        </tr>
                    </thead>

                    <tbody class="bg-gray-900 divide-y divide-gray-800">
                        @forelse($bitacoras as $bitacora)
                            <tr class="hover:bg-gray-800 transition">
                                <td class="px-6 py-4 text-white">{{ $bitacora->usuario }}</td>
                                <td class="px-6 py-4 text-white">{{ $bitacora->accion }}</td>
                                <td class="px-6 py-4 text-white">{{ $bitacora->detalle }}</td>
                                <td class="px-6 py-4 text-white">{{ $bitacora->modulo }}</td>
                                <td class="px-6 py-4 text-white">{{ \Carbon\Carbon::parse($bitacora->created_at)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 bg-gray-900">
                                    No hay registros para el filtro seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 🔹 Paginación --}}
            <div class="mt-4">{{ $bitacoras->links() }}</div>
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
