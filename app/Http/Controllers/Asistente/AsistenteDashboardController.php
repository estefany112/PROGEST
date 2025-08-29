<?php

namespace App\Http\Controllers\Asistente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cotizacion;

class AsistenteDashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->getRoleNames()->first();
        $totalUsuarios = \App\Models\User::count(); 
        $totalCotizaciones = \App\Models\Cotizacion::count();

        return view('asistente.dashboard', compact('role', 'totalUsuarios', 'totalCotizaciones'));
    }
}
