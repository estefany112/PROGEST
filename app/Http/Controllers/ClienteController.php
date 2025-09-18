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

        $cliente = Cliente::create($data);

        if ($request->expectsJson()) {
            // Para el select solo necesitas estos:
            return response()->json([
                'ok'      => true,
                'cliente' => $cliente->only(['id','nombre','nit','direccion'])
            ], 201);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado.');
}
    // === LISTADO con búsqueda/paginación ===
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $clientes = Cliente::query()
            ->when($q, fn($qry) => $qry->where(function($w) use ($q) {
                $w->where('nombre','like',"%{$q}%")
                  ->orWhere('nit','like',"%{$q}%")
                  ->orWhere('direccion','like',"%{$q}%");
            }))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes','q'));
    }

    // === FORM crear ===
    public function create()
    {
        return view('clientes.create');
    }

    // (Opcional) Si quisieras usar REST estándar, podrías redirigir a guardar():
    // public function store(Request $request) { return $this->guardar($request); }

    // === VER detalle ===
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    // === FORM editar ===
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // === Actualizar ===
    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre'    => ['required','string','max:150'],
            'nit'       => ['required','string','max:50','unique:clientes,nit,'.$cliente->id],
            'direccion' => ['required','string'],
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado.');
    }

    // === Eliminar ===
    public function destroy(Cliente $cliente)
    {
        // Si quieres restringir eliminación cuando existan cotizaciones:
        // if ($cliente->cotizaciones()->exists()) {
        //     return back()->with('error','No se puede eliminar: tiene cotizaciones.');
        // }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado.');
    }
}

