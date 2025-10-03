<?php

namespace App\Http\Controllers;

use App\Models\ContrasenaPago;
use App\Models\Factura;
use Illuminate\Http\Request;

class ContrasenaPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contrasenas = ContrasenaPago::with('factura.ordenCompra.cotizacion.cliente')->latest()->paginate(10);
        return view('contrasenas.index', compact('contrasenas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facturas = Factura::all();
        return view('contrasenas.create', compact('facturas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        dd($request->all());
        $request->validate([
            'factura_id' => 'required|exists:facturas,id',
            'codigo'     => 'required|string|max:255',
            'fecha_documento' => 'required|date',
            'archivo'    => 'nullable|file|max:5120',
            'fecha_aprox'=> 'nullable|date',
        ]);

        $archivoPath = null;
        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('contrasenas', 'public');
        }

        ContrasenaPago::create([
            'factura_id' => $request->factura_id,
            'codigo'     => $request->codigo,
            'fecha_documento' => $request->fecha_documento,
            'archivo'    => $archivoPath,
            'fecha_aprox'=> $request->fecha_aprox,
        ]);

        return redirect()->route('contrasenas.index')->with('success', 'Contraseña registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContrasenaPago $contrasena)
    {
         $contrasena->load('factura.ordenCompra.cotizacion.cliente');
        return view('contrasenas.show', compact('contrasena'));
    }

    /**
     * Metodo Actualizar
    */
    public function update(Request $request, ContrasenaPago $contrasena)
    {
        $request->validate([
            'codigo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,validada',
            'fecha_aprox' => 'nullable|date',
            'archivo' => 'nullable|file|max:5120',
            'fecha_documento' => 'nullable|date',
        ]);

        if ($request->hasFile('archivo')) {
            $contrasena->archivo = $request->file('archivo')->store('contrasenas', 'public');
        }

        $contrasena->codigo = $request->codigo;
        $contrasena->estado = $request->estado;
        $contrasena->fecha_documento = $request->fecha_documento;
        $contrasena->fecha_aprox = $request->fecha_aprox;
        if ($request->estado === 'validada' && !$contrasena->validada_en) {
            $contrasena->validada_en = now();
        }
        $contrasena->save();

        return redirect()->route('contrasenas.index')->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContrasenaPago $contrasena)
    {
        $contrasena->load('factura.ordenCompra.cotizacion.cliente');
        return view('contrasenas.edit', compact('contrasena'));
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContrasenaPago $contrasena)
    {
        $contrasena->delete();
        return redirect()->route('contrasenas.index')->with('success', 'Contraseña eliminada correctamente.');
    }
}
