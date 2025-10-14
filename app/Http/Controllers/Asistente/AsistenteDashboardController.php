<?php

namespace App\Http\Controllers\Asistente;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ContrasenaPago;
use App\Models\Cotizacion;
use App\Models\OrdenCompra;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\ReporteTrabajo;
use Illuminate\Support\Facades\Auth;

class AsistenteDashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->getRoleNames()->first();
        $user = Auth::user();

        $totalClientes = Cliente::count();
        $totalCotizaciones = Cotizacion::where('creada_por', $user->id)->count();
        $totalOrdenesCompra = OrdenCompra::where('creada_por', $user->id)->count();
        $totalFactura = Factura::where('creada_por', $user->id)->count();
        $totalReporteTrabajo = ReporteTrabajo::where('creada_por', $user->id)->count();
        $totalContrasenas = ContrasenaPago::where('creada_por', $user->id)->count();
        $totalPago = Pago::where('creada_por', $user->id)->count();

        return view('asistente.dashboard', compact(
            'role', 
            'totalClientes',
            'totalCotizaciones',
            'totalOrdenesCompra',
            'totalFactura',
            'totalReporteTrabajo',
            'totalContrasenas',
            'totalPago'
        ));
    }
}

