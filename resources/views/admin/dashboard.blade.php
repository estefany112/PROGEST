@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-6">
    <!-- Encabezado -->
    <div class="flex items-center mb-10">
        <h1 class="text-3xl font-bold text-white">Panel de Administrador</h1>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid md:grid-cols-4 gap-6 mb-10">
        <!-- Total Usuarios -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Usuarios</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalUsuarios }}</h2>
                </div>
                <div class="bg-blue-600 text-white p-2 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
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

        <!-- Pagos -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 shadow hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 uppercase">Pagos</p>
                    <h2 class="text-2xl font-bold text-white">{{ $totalPago}}</h2>
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
                    <h2 class="text-2xl font-bold text-white">0 </h2>
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
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Gestión de Usuarios -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <div class="flex items-center mb-2 text-white">
                <svg class="w-6 h-6 mr-2 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M5.121 17.804A9.969 9.969 0 0112 15c2.485 0 4.735.91 6.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-xl font-semibold">Gestión de Usuarios</h3>
            </div>
            <p class="text-gray-400 mb-4">Administra los usuarios registrados, aprueba o rechaza solicitudes y gestiona roles.</p>
            <a href="{{ route('usuarios.index') }}" 
            class="text-blue-400 hover:text-blue-300 font-medium">Ir a usuarios →</a>
        </div>

        <!-- Cotizaciones -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Cotizaciones</h3>
            <p class="text-gray-400 mb-4">Ver y administrar cotizaciones realizadas.</p>
            <a href="{{ route('cotizaciones.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a cotizaciones →</a>
        </div>

        <!-- Órdenes de Compra -->
        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-white mb-2">Órdenes de Compra</h3>
            <p class="text-gray-400 mb-4">Gestiona las órdenes vinculadas a cotizaciones.</p>
            <a href="{{ route('ordenes-compra.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a órdenes →</a>
        </div>

        <!-- Reportes de Trabajo -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
        <h3 class="text-xl font-semibold text-white mb-2">Reportes de Trabajo</h3>
        <p class="text-gray-400 mb-4">Adjunta documentos de evidencia de servicios realizados.</p>
        <a href="{{ route('reportes-trabajo.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a reportes →</a>
    </div>
    
       <!-- Facturas -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
        <h3 class="text-xl font-semibold text-white mb-2">Facturas</h3>
        <p class="text-gray-400 mb-4">Gestiona las facturas registradas y vinculadas a órdenes de compra.</p>
        <a href="{{ route('facturas.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a facturas →</a>
    </div>

    <!-- Pagos -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
        <h3 class="text-xl font-semibold text-white mb-2">Pagos</h3>
        <p class="text-gray-400 mb-4">Registra y consulta el estado de los pagos de facturas.</p>
        <a href="{{ route('pagos.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a pagos →</a>
    </div>

    <!-- Contraseñas de Pago -->
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow hover:shadow-xl transition">
        <h3 class="text-xl font-semibold text-white mb-2">Contraseñas de Pago</h3>
        <p class="text-gray-400 mb-4">Valida las contraseñas asociadas a facturas antes de su cancelación.</p>
        <a href="{{ route('contraseñas.index') }}" class="text-blue-400 hover:text-blue-300 font-medium">Ir a contraseñas →</a>
    </div>

    </div>
    </div>
@endsection
