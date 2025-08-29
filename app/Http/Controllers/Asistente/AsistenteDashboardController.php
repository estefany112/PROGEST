<?php

namespace App\Http\Controllers\Asistente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cotizacion;

class AsistenteDashboardController extends Controller
{
    public function index()
    {
         $role = 'asistente';

        $totalUsuarios = 0; // normalmente admin ve esto; puedes ocultarlo en la vista si quieres
        $totalClientes = Cliente::count();
        $totalCotizaciones = Cotizacion::where('creada_por', Auth::id())->count();

        // (Opcional) más métricas propias del asistente
        $cotizacionesAprob = Cotizacion::where('creada_por', Auth::id())->where('estado','aprobada')->count();
        $cotizacionesRev   = Cotizacion::where('creada_por', Auth::id())->where('estado','en_revision')->count();
        $cotizacionesBorr  = Cotizacion::where('creada_por', Auth::id())->where('estado','borrador')->count();

        return view('asistente.dashboard', compact(
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