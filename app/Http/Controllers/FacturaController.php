<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\OrdenCompra;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    /**
     * Listado de facturas.
     */
    public function index()
    {
        $facturas = Factura::with(['ordenCompra.cotizacion.cliente'])
            ->latest()
            ->paginate(10);

        return view('facturas.index', compact('facturas'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        // Solo ordenes con cotización aprobada
        $ordenes = OrdenCompra::with('cotizacion.cliente')
            ->latest()
            ->get();

        return view('facturas.create', compact('ordenes'));
    }

    /**
     * Guardar nueva factura.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'numero_factura'  => 'required|string|max:50|unique:facturas,numero_factura',
            'fecha_emision'   => 'required|date',
            'monto_total'     => 'required|numeric|min:0',
            'archivo_pdf_path'=> 'nullable|file|max:10240'
        ]);

        // Traer orden y su cliente
        $orden = OrdenCompra::with('cotizacion.cliente')
            ->findOrFail($request->orden_compra_id);

        $data = [
            'orden_compra_id' => $request->orden_compra_id,
            'cliente_id'      => $orden->cotizacion->cliente_id,
            'numero_factura'  => $request->numero_factura,
            'fecha_emision'   => $request->fecha_emision,
            'monto_total'     => $request->monto_total,
            'creada_por' => Auth::id(),
        ];

        if ($request->hasFile('archivo_pdf_path')) {
            $data['archivo_pdf_path'] = $request->file('archivo_pdf_path')->store('facturas', 'public');
        }

        Factura::create($data);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura creada correctamente.');
    }

    /**
     * Mostrar detalle de una factura.
     */
    public function show($id)
    {
        $factura = Factura::with(['ordenCompra.cotizacion.cliente'])
            ->findOrFail($id);

        return view('facturas.show', compact('factura'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $factura = Factura::findOrFail($id);
        $ordenes = OrdenCompra::with('cotizacion.cliente')->get();

        return view('facturas.edit', compact('factura', 'ordenes'));
    }

    /**
     * Actualizar factura.
     */
    public function update(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);

        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'numero_factura'  => 'required|string|max:50|unique:facturas,numero_factura,' . $factura->id,
            'fecha_emision'   => 'required|date',
            'monto_total'     => 'required|numeric|min:0',
            'archivo_pdf_path'=> 'nullable|file|max:10240'
        ]);

        $data = $request->only(['orden_compra_id', 'numero_factura', 'fecha_emision', 'monto_total']);

        if ($request->hasFile('archivo_pdf_path')) {
            $data['archivo_pdf_path'] = $request->file('archivo_pdf_path')->store('facturas', 'public');
        }

        // Asegurar que se actualiza el cliente según la orden
        $orden = OrdenCompra::with('cotizacion.cliente')->findOrFail($request->orden_compra_id);
        $data['cliente_id'] = $orden->cotizacion->cliente_id;

        $factura->update($data);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    /**
     * Eliminar factura.
     */
    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada correctamente.');
    }
}
