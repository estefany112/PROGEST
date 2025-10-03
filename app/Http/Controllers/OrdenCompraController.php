<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCompra;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Auth;

class OrdenCompraController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'asistente') {
            // Solo las órdenes que el asistente creó
            $ordenes = OrdenCompra::with('cotizacion')
                ->where('creada_por', Auth::id())
                ->latest()
                ->paginate(10);
        } else {
            // Admin ve todas
            $ordenes = OrdenCompra::with('cotizacion')
                ->latest()
                ->paginate(10);
        }

        return view('ordenes_compra.index', compact('ordenes'));
    }

    public function create($cotizacionId = null)
    {
        // Solo mostrar cotizaciones aprobadas
        $cotizaciones = Cotizacion::where('estado', 'aprobada')->get();

        $cotizacion = null;
        if ($cotizacionId) {
            $cotizacion = Cotizacion::findOrFail($cotizacionId);

            if ($cotizacion->estado !== 'aprobada') {
                return redirect()->route('cotizaciones.index')
                    ->with('error', 'Solo puedes generar una orden de compra desde una cotización aprobada.');
            }
        }

        return view('ordenes_compra.create', compact('cotizaciones', 'cotizacion'));
    }

    public function store(Request $request)
    {
     
        $request->validate([
            'cotizacion_id'   => 'required|exists:cotizaciones,id',
            'numero_oc'       => 'required|unique:ordenes_compra,numero_oc',
            'fecha'           => 'required|date',
            'monto_total'     => 'required|numeric|min:0',
            'archivo_oc_path' => 'nullable|file|mimes:pdf,jpg,png'
        ]);

        $data = $request->only(['cotizacion_id', 'numero_oc', 'fecha', 'monto_total']);
        $data['creada_por'] = Auth::id();

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

    public function show($id)
    {
       $orden = OrdenCompra::with('cotizacion')->findOrFail($id);
        return view('ordenes_compra.show', compact('orden'));
    }

    public function edit($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $cotizaciones = Cotizacion::where('estado', 'aprobada')->get();

        return view('ordenes_compra.edit', compact('orden', 'cotizaciones'));
    }

    public function update(Request $request, $id)
    {
        $orden = OrdenCompra::findOrFail($id);

        $request->validate([
            'cotizacion_id'   => 'required|exists:cotizaciones,id',
            'numero_oc'       => 'required|unique:ordenes_compra,numero_oc,' . $orden->id,
            'fecha'           => 'required|date',
            'monto_total'     => 'nullable|numeric',
            'archivo_oc_path' => 'nullable|file|mimes:pdf,jpg,png'
        ]);

        $data = $request->only(['cotizacion_id', 'numero_oc', 'fecha', 'monto_total']);

        if ($request->hasFile('archivo_oc_path')) {
            $data['archivo_oc_path'] = $request->file('archivo_oc_path')->store('ordenes', 'public');
        }

        $orden->update($data);

        return redirect()->route('ordenes-compra.index')
            ->with('success', 'Orden de compra actualizada correctamente.');
    }

    public function destroy($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $orden->delete();

        return redirect()->route('ordenes-compra.index')
            ->with('success', 'Orden de compra eliminada correctamente.');
    }
}
