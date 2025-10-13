@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Encabezado -->
    <div class="flex items-center mb-10">
        <h1 class="text-3xl font-bold text-white">Panel del Asistente</h1>
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
                <div class="bg-green-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
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
                <div class="bg-indigo-600 text-white p-2 rounded-full">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 10h6m-6 4h6" />
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
                <div class="bg-gray-500 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2l4-4m2-6H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V8l-4-4z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reportes de Trabajo -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Reportes de Trabajo</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalReporteTrabajo }}</h2>
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
                    <h2 class="text-2xl font-bold text-white">{{ $totalFactura }}</h2>
                </div>
                <div class="bg-purple-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M12 8v8m0 0l-3-3m3 3l3-3M20 12A8 8 0 104 12a8 8 0 0016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contraseñas de pago -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Contraseñas de Pago</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalContrasenas }}</h2>
                </div>
                <div class="bg-red-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <!-- Cuerpo del candado -->
                    <rect x="5" y="11" width="14" height="10" rx="2" ry="2" />
                    <!-- Arco superior -->
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 11V7a4 4 0 018 0v4" />
                    <!-- Detalle de cerradura -->
                    <circle cx="12" cy="16" r="1.5" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pagos -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Pagos</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalPago}}</h2>
                </div>
                <div class="bg-green-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="6" width="18" height="12" rx="2" ry="2" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE ACCESO DIRECTO -->
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Clientes -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Clientes</h3>
            <p class="text-gray-400 mb-4">Crea y gestiona información sobre clientes para la realización de una cotización.</p>
            <div class="flex gap-3">
                <a href="{{ route('clientes.create') }}" class="text-green-400 hover:text-green-300 font-medium">Nuevo cliente →</a>
                <a href="{{ route('clientes.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver clientes →</a>
            </div>
        </div>
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
            <a href="{{ route('ordenes-compra.create') }}" class="text-green-400 hover:text-green-300 font-medium">Nueva Orden de Compra →</a>
            <a href="{{ route('ordenes-compra.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver órdenes →</a>
        </div>

         <!-- Reportes -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Reportes</h3>
            <p class="text-gray-400 mb-4">Adjunta reportes de servicios finalizados.</p>
            <a href="{{ route('reportes-trabajo.create') }}" class="text-green-400 hover:text-green-300 font-medium">Subir reporte →</a> </br>
            <a href="{{ route('reportes-trabajo.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver reporte →</a>
        </div>
        
        <!-- Facturas -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Facturas</h3>
            <p class="text-gray-400 mb-4">Sube facturas externas emitidas por clientes.</p>
            <a href="{{ route('facturas.create') }}" class="text-blue-400 hover:text-blue-300 font-medium">Subir factura →</a>
        </div>

        <!-- Contraseñas de Pago -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Contraseñas de Pago</h3>
            <p class="text-gray-400 mb-4">Consulta o genera contraseñas de autorización.</p>
            <a href="{{ route('contrasenas.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ver contraseñas →</a>
        </div>

        <!-- Pagos -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Pagos</h3>
            <p class="text-gray-400 mb-4">Registra y verifica pagos realizados.</p>
            <a href="{{ route('pagos.index') }}" class="text-green-400 hover:text-green-300 font-medium">Ver pagos →</a>
        </div>

    </div>
</div>
@endsection
