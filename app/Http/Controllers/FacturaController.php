<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\OrdenCompra;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facturas = Factura::with('ordenCompra')->latest()->paginate(10);
        return view('facturas.index', compact('facturas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ordenes = \App\Models\OrdenCompra::with('cotizacion')->latest()->get();
        return view('facturas.create', compact('ordenes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'cotizacion_id' => 'required|exists:cotizaciones,id',
        'numero_oc'     => 'required|unique:ordenes_compra,numero_oc',
        'fecha'         => 'required|date',
        'monto_total'   => 'required|numeric|min:0',
        'archivo_oc_path' => 'nullable|file|mimes:pdf,jpg,png'
    ]);

    $data = $request->only(['cotizacion_id', 'numero_oc', 'fecha', 'monto_total']);

    if ($request->hasFile('archivo_oc_path')) {
        $data['archivo_oc_path'] = $request->file('archivo_oc_path')->store('ordenes', 'public');
    }

    $cotizacion = Cotizacion::findOrFail($request->cotizacion_id);

    if ($cotizacion->estado !== 'aprobada') {
        return redirect()->route('cotizaciones.index')
            ->with('error', 'No puedes crear una orden de compra desde una cotización que no está aprobada.');
    }

    OrdenCompra::create($data);

    return redirect()->route('ordenes-compra.index')
        ->with('success', 'Orden de compra creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('facturas.show', compact('factura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ordenes = OrdenCompra::all();
        return view('facturas.edit', compact('factura', 'ordenes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'orden_compra_id'  => 'required|exists:ordenes_compra,id',
            'numero'           => 'required|string',
            'fecha_emision'    => 'required|date',
            'monto'            => 'required|numeric',
            'archivo_pdf_path' => 'nullable|file|mimes:pdf'
        ]);

        $data = $request->all();
        if ($request->hasFile('archivo_pdf_path')) {
            $data['archivo_pdf_path'] = $request->file('archivo_pdf_path')->store('facturas', 'public');
        }

        $factura->update($data);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $factura->delete();
        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}
