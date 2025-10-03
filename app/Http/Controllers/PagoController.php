<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       if (Auth::user()->role === 'asistente') {
        $pagos = Pago::where('creada_por', Auth::id())
            ->with('factura.ordenCompra.cotizacion.cliente')
            ->latest()
            ->paginate(10);
        } else {
            $pagos = Pago::with('factura.ordenCompra.cotizacion.cliente')
                ->latest()
                ->paginate(10);
        }

        return view('pagos.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $facturas = Factura::all();
        return view('pagos.create', compact('facturas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'factura_id' => 'required|exists:facturas,id',
            'estado'     => 'required|in:pendiente,pagado',
            'fecha_pago' => 'nullable|date',
        ]);

        // Si se marca como pagado y no hay fecha, asignar hoy
        if ($request->estado === 'pagado' && !$request->fecha_pago) {
            $request->merge(['fecha_pago' => now()]);
        }

       Pago::create([
        'factura_id' => $request->factura_id,
        'estado'     => $request->estado,
        'fecha_pago' => $request->fecha_pago,
        'creada_por' => Auth::id(),
    ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
  
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        // Cargar también las relaciones necesarias
        $pago->load('factura.ordenCompra.cotizacion.cliente');

        return view('pagos.show', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        $facturas = Factura::all();
        return view('pagos.edit', compact('pago', 'facturas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'estado'     => 'required|in:pendiente,pagado',
            'fecha_pago' => 'nullable|date',
        ]);

        if ($request->estado === 'pagado' && !$request->fecha_pago) {
            $request->merge(['fecha_pago' => now()]);
        }

        $pago->update($request->all());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado.');
    }
}
