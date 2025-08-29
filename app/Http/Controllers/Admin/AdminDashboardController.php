<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Cotizacion;

class AdminDashboardController extends Controller
{
    public function index()
    {
    $role = 'admin';

        $totalUsuarios      = User::count();
        $totalClientes      = Cliente::count();
        $totalCotizaciones  = Cotizacion::count();

        // (Opcional) más métricas:
        $cotizacionesAprob  = Cotizacion::where('estado','aprobada')->count();
        $cotizacionesRev    = Cotizacion::where('estado','en_revision')->count();
        $cotizacionesBorr   = Cotizacion::where('estado','borrador')->count();

        return view('admin.dashboard', compact(
            'role',
            'totalUsuarios',
            'totalClientes',
            'totalCotizaciones',
            'cotizacionesAprob',
            'cotizacionesRev',
            'cotizacionesBorr'
        ));
    }
}
