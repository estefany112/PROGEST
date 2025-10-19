<?php

namespace App\Http\Controllers;
use App\Models\Bitacora;

use Illuminate\Http\Request;

class BitacoraController extends Controller
{
   public function index(Request $request)
    {
        // Filtrar por acción si se ha seleccionado
        $query = Bitacora::query();

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        // Solo los administradores pueden acceder
        $this->authorize('viewAny', Bitacora::class);
        
        // Obtener todos los registros de la bitácora, ordenados por fecha
        $bitacoras = $query->orderBy('created_at', 'desc')->paginate(10);
        $bitacoras->appends($request->all());

        // Pasar los registros a la vista
        return view('bitacora.index', compact('bitacoras'));
    }
}
