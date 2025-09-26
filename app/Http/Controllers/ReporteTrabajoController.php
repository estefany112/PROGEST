<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReporteTrabajo;
use App\Models\OrdenCompra;

class ReporteTrabajoController extends Controller
{
    public function index()
    {
        $reportes = ReporteTrabajo::with('ordenCompra')->latest()->paginate(10);
        return view('reportes.index', compact('reportes'));
    }

    public function create()
    {
        $ordenes = OrdenCompra::with('cotizacion.cliente')->get();
        return view('reportes.create', compact('ordenes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'archivo'         => 'required|file|max:5120' // hasta 5MB, cualquier extensión
        ]);

        $path = $request->file('archivo')->store('reportes', 'public');

        ReporteTrabajo::create([
            'orden_compra_id' => $request->orden_compra_id,
            'archivo' => $path
        ]);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo adjuntado correctamente.');
    }

    public function show($id)
    {
        $reporte = ReporteTrabajo::with('ordenCompra')->findOrFail($id);
        return view('reportes.show', compact('reporte'));
    }

    public function destroy($id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);
        $reporte->delete();

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte eliminado correctamente.');
    }

    public function edit($id)
    {
        $reporte = ReporteTrabajo::with('ordenCompra')->findOrFail($id);
        $ordenes = OrdenCompra::with('cotizacion.cliente')->get();

        return view('reportes.edit', compact('reporte', 'ordenes'));
    }

    public function update(Request $request, $id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'archivo'         => 'nullable|file|max:5120' // hasta 5MB, cualquier extensión
        ]);

        $data = $request->only(['orden_compra_id']);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('reportes', 'public');
        }

        $reporte->update($data);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo actualizado correctamente.');
    }

}
