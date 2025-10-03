<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContrasenaPago;
use App\Models\User;
use App\Models\Cotizacion;
use App\Models\OrdenCompra;
use App\Models\Factura;
use App\Models\ReporteTrabajo;
use App\Models\Pago;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();

        // Contadores globales
        $totalUsuarios     = User::count();
        $totalCotizaciones = Cotizacion::visiblePara(auth()->user())->count();
        $totalOrdenesCompra = OrdenCompra::count();
        $totalFactura = Factura::count();
        $totalReporteTrabajo = ReporteTrabajo::count();
        $totalPago = Pago::count();
        $totalContrasenas = ContrasenaPago::count();

        return view('admin.dashboard', compact(
            'role',
            'totalUsuarios',
            'totalCotizaciones',
            'totalOrdenesCompra',
            'totalFactura',
            'totalReporteTrabajo',
            'totalPago',
            'totalContrasenas',
        ));
    }
}
