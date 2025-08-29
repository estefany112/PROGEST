<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Devuelve clientes para poblar el select
    public function listaJson()
    {
        return response()->json(
            Cliente::orderBy('nombre')->get(['id','nombre','nit','direccion'])
        );
    }

    // Crear cliente (sirve para formulario normal y para AJAX)
    public function guardar(Request $request)
    {
        $data = $request->validate([
            'nombre'    => ['required','string','max:150'],
            'nit'       => ['required','string','max:50','unique:clientes,nit'],
            'direccion' => ['required','string'],
        ]);

        $cliente = \App\Models\Cliente::create($data);

        if ($request->expectsJson()) {
            // Para el select solo necesitas estos:
            return response()->json([
                'ok'      => true,
                'cliente' => $cliente->only(['id','nombre','nit','direccion'])
            ], 201);
        }

        return back()->with('success','Cliente creado.');
    }
}
