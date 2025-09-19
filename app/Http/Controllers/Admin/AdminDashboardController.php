<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cotizacion;
use App\Models\OrdenCompra;
use App\Models\Factura;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();

        // Contadores globales
        $totalUsuarios     = User::count();
        $totalCotizaciones = Cotizacion::count();
        $totalOrdenesCompra = OrdenCompra::count();
       

        return view('admin.dashboard', compact(
            'role',
            'totalUsuarios',
            'totalCotizaciones',
            'totalOrdenesCompra',
            
        ));
    }
}
