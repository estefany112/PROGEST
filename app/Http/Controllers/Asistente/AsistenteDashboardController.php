<?php

namespace App\Http\Controllers\Asistente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AsistenteDashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->getRoleNames()->first();
        $totalUsuarios = \App\Models\User::count(); 

        return view('asistente.dashboard', compact('role', 'totalUsuarios'));
    }
}
