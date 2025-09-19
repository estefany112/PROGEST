<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cotizacion;

class AsistenteCotizacionesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $cotizaciones = Cotizacion::where('creada_por', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('cotizaciones', compact('cotizaciones'));
    }
}
