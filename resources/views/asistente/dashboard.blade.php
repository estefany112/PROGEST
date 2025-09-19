@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Encabezado con logo -->
    <div class="flex items-center mb-10">
        <h1 class="text-3xl font-bold text-white">Panel de {{ ucfirst($role) }}</h1>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid md:grid-cols-4 gap-6 mb-10">
        <!-- Clientes -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Clientes</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalClientes }}</h2>
                </div>
                <div class="bg-blue-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5V4H2v16h5m5-16v16" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Cotizaciones -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Cotizaciones</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalCotizaciones }}</h2>
                </div>
                <div class="bg-green-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Órdenes de Compra -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Órdenes de Compra</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalOrdenesCompra }}</h2>
                </div>
                <div class="bg-yellow-500 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12h6m2 6H7a2 2 0 01-2-2V8a2 2 0 012-2h4l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Facturas -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Facturas</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalFacturas ?? 0 }}</h2>
                </div>
                <div class="bg-purple-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 8v8m0 0l-3-3m3 3l3-3M20 12A8 8 0 104 12a8 8 0 0016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE ACCESO DIRECTO -->
    @if($role === 'admin')
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Cotizaciones -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M9 14l6-6M15 14l-6-6" />
                    </svg>
                    <h3 class="text-xl font-semibold">Cotizaciones</h3>
                </div>
                <p class="text-gray-400 mb-4">Revisa, aprueba o rechaza cotizaciones enviadas.</p>
                <a href="{{ route('asistente.cotizaciones.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a cotizaciones →</a>
            </div>

            <!-- Órdenes de Compra -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    <h3 class="text-xl font-semibold">Órdenes de Compra</h3>
                </div>
                <p class="text-gray-400 mb-4">Valida y supervisa las órdenes creadas por asistentes.</p>
                <a href="{{ route('ordenes-compra.index') }}" class="text-yellow-400 hover:text-yellow-300 font-medium">Ver órdenes →</a>
            </div>

            <!-- Usuarios -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M17 20h5V4H2v16h5m5-16v16" />
                    </svg>
                    <h3 class="text-xl font-semibold">Gestión de usuarios</h3>
                </div>
                <p class="text-gray-400 mb-4">Aprobar o rechazar usuarios registrados.</p>
                <a href="{{ route('admin.usuarios.pendientes') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver usuarios →</a>
            </div>
        </div>

    @elseif($role === 'asistente')
        <div class="grid md:grid-cols-4 gap-6">
            <!-- Crear cotización -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-green-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                    <h3 class="text-xl font-semibold">Crear cotización</h3>
                </div>
                <p class="text-gray-400 mb-4">Genera cotizaciones rápidamente.</p>
                <a href="{{ route('cotizaciones.create') }}" class="text-green-400 hover:text-green-300 font-medium">Cotizar ahora →</a>
            </div>

            <!-- Ver cotizaciones -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M9 14l6-6M15 14l-6-6" />
                    </svg>
                    <h3 class="text-xl font-semibold">Mis Cotizaciones</h3>
                </div>
                <p class="text-gray-400 mb-4">Ver y gestionar tus cotizaciones.</p>
                <a href="{{ route('cotizaciones.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver cotizaciones →</a>
            </div>

            <!-- Órdenes de Compra -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" />
                    </svg>
                    <h3 class="text-xl font-semibold">Órdenes de Compra</h3>
                </div>
                <p class="text-gray-400 mb-4">Crea y gestiona órdenes de compra de cotizaciones aprobadas.</p>
                <div class="flex gap-3">
                    <a href="{{ route('ordenes-compra.index') }}" class="text-yellow-400 hover:text-yellow-300 font-medium">Ver órdenes →</a>
                    <a href="{{ route('ordenes-compra.create') }}" class="text-green-400 hover:text-green-300 font-medium">Nueva orden →</a>
                </div>
            </div>

            <!-- Subir factura -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
                <div class="flex items-center mb-2 text-white">
                    <svg class="w-6 h-6 mr-2 text-purple-400" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 8v8m0 0l-3-3m3 3l3-3M20 12A8 8 0 104 12a8 8 0 0016 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold">Subir factura</h3>
                </div>
                <p class="text-gray-400 mb-4">Registra facturas externas emitidas.</p>
                <a href="{{ route('facturas.create') }}" class="text-purple-400 hover:text-purple-300 font-medium">Subir factura →</a>
            </div>
        </div>
    @endif
</div>
@endsection
