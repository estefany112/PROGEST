<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cotizacion;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();

        // Contadores globales
        $totalUsuarios     = User::count();
        $totalCotizaciones = Cotizacion::count();

        return view('admin.dashboard', compact(
            'role',
            'totalUsuarios',
            'totalCotizaciones',
        ));
    }
}
