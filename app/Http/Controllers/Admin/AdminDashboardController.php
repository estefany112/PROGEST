<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Cotizacion;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
    $role = 'admin';

        $totalUsuarios      = User::count();
        $totalClientes      = Cliente::count();
        $usuariosRechazados = User::where('estado', 'rechazado')->count();
        $totalCotizaciones  = Cotizacion::count();
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
