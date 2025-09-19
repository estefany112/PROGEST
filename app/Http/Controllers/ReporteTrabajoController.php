<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReporteTrabajo;
use App\Models\OrdenCompra;

class ReporteTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportes = ReporteTrabajo::with('ordenCompra')->latest()->paginate(10);
        return view('reportes_trabajo.index', compact('reportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ordenes = OrdenCompra::all();
        return view('reportes_trabajo.create', compact('ordenes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date',
            'descripcion'     => 'nullable|string',
            'archivo_reporte_path' => 'nullable|file|mimes:pdf,jpg,png'
        ]);

        $data = $request->all();
        if ($request->hasFile('archivo_reporte_path')) {
            $data['archivo_reporte_path'] = $request->file('archivo_reporte_path')->store('reportes', 'public');
        }

        ReporteTrabajo::create($data);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo creado correctamente.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('reportes_trabajo.show', compact('reporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ordenes = OrdenCompra::all();
        return view('reportes_trabajo.edit', compact('reporte', 'ordenes'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date',
            'descripcion'     => 'nullable|string',
            'archivo_reporte_path' => 'nullable|file|mimes:pdf,jpg,png'
        ]);

        $data = $request->all();
        if ($request->hasFile('archivo_reporte_path')) {
            $data['archivo_reporte_path'] = $request->file('archivo_reporte_path')->store('reportes', 'public');
        }

        $reporte->update($data);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo actualizado correctamente.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $reporte->delete();
        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo eliminado correctamente.');
    }
}
