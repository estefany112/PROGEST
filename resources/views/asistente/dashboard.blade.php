@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Encabezado -->
    <div class="flex items-center mb-10">
        <h1 class="text-3xl font-bold text-white">Panel del Asistente</h1>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid md:grid-cols-4 gap-6 mb-10">
        <!-- Cotizaciones -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Cotizaciones</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalCotizaciones }}</h2>
                </div>
                <div class="bg-green-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
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
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
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
                    <h2 class="text-2xl font-bold text-white">{{ $totalFacturas }}</h2>
                </div>
                <div class="bg-purple-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 8v8m0 0l-3-3m3 3l3-3M20 12A8 8 0 104 12a8 8 0 0016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reportes -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Reportes</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalReportes }}</h2>
                </div>
                <div class="bg-blue-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M17 20h5V4H2v16h5m5-16v16" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE ACCESO DIRECTO -->
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Cotizaciones -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Cotizaciones</h3>
            <p class="text-gray-400 mb-4">Crea y gestiona tus cotizaciones.</p>
            <div class="flex gap-3">
                <a href="{{ route('cotizaciones.create') }}" class="text-green-400 hover:text-green-300 font-medium">Nueva cotización →</a>
                <a href="{{ route('cotizaciones.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver cotizaciones →</a>
            </div>
        </div>

        <!-- Órdenes de Compra -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Órdenes de Compra</h3>
            <p class="text-gray-400 mb-4">Genera órdenes desde cotizaciones aprobadas.</p>
            <a href="{{ route('ordenes-compra.index') }}" class="text-yellow-400 hover:text-yellow-300 font-medium">Ver órdenes →</a>
        </div>

        <!-- Facturas -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Facturas</h3>
            <p class="text-gray-400 mb-4">Sube facturas externas emitidas por clientes.</p>
            <a href="{{ route('facturas.create') }}" class="text-purple-400 hover:text-purple-300 font-medium">Subir factura →</a>
        </div>

        <!-- Reportes -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Reportes</h3>
            <p class="text-gray-400 mb-4">Adjunta reportes de servicios finalizados.</p>
            <a href="{{ route('reportes-trabajo.create') }}" class="text-blue-400 hover:text-blue-300 font-medium">Subir reporte →</a>
        </div>
    </div>
</div>
@endsection
