<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ReporteTrabajo;
use App\Models\OrdenCompra;
use Illuminate\Support\Facades\Auth;

class ReporteTrabajoController extends Controller
{
    /**
     * Mostrar listado según rol
     */
    public function index()
    {
        $user = Auth::user();

        $query = ReporteTrabajo::with(['ordenCompra.cotizacion', 'creadaPor']);

        // Si es asistente → solo ve los suyos
        if ($user->hasRole('asistente')) {
            $query->where('creada_por', $user->id);
        }

        // Si es admin → NO ve los borradores
        if ($user->hasRole('admin')) {
            $query->where('status', '!=', 'borrador');
        }

        // Filtro por estado manual (desde el select del filtro)
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $reportes = $query->latest()->paginate(10);

        return view('reportes.index', compact('reportes'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Solo mostrar órdenes aprobadas
        $ordenes = OrdenCompra::where('status', 'aprobado')->with('cotizacion.cliente')->get();
        return view('reportes.create', compact('ordenes'));
    }

    /**
     * Guardar reporte (estado inicial: borrador)
     */
    public function store(Request $request)
    {
        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'archivo'         => 'required|file|max:5120'
        ]);

        $path = $request->file('archivo')->store('reportes', 'public');

        ReporteTrabajo::create([
            'orden_compra_id' => $request->orden_compra_id,
            'archivo'         => $path,
            'creada_por'      => Auth::id(),
            'status'          => 'borrador',
        ]);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte creado como borrador.');
    }

    /**
     * Mostrar detalle
     */
    public function show($id)
    {
        $reporte = ReporteTrabajo::with('ordenCompra')->findOrFail($id);
        return view('reportes.show', compact('reporte'));
    }

    /**
     * Editar (solo si es borrador)
     */
    public function edit($id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        if ($reporte->status !== 'borrador' && Auth::user()->role === 'asistente') {
            return redirect()->route('reportes-trabajo.index')
                ->with('error', 'Solo puedes editar reportes en estado borrador.');
        }

        $ordenes = OrdenCompra::where('status', 'aprobado')->with('cotizacion.cliente')->get();
        return view('reportes.edit', compact('reporte', 'ordenes'));
    }

    /**
     * Actualizar datos
     */
    public function update(Request $request, $id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        $request->validate([
            'orden_compra_id' => 'required|exists:ordenes_compra,id',
            'archivo'         => 'nullable|file|max:5120'
        ]);

        $data = $request->only(['orden_compra_id']);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('reportes', 'public');
        }

        $reporte->update($data);

        return redirect()->route('reportes-trabajo.index')
            ->with('success', 'Reporte de trabajo actualizado correctamente.');
    }

    /**
     * Enviar a revisión (solo asistente)
     */
    public function enviarRevision($id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        if ($reporte->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar reportes en borrador.');
        }

        $reporte->update(['status' => 'revision']);

        return back()->with('success', 'Reporte enviado a revisión.');
    }

    /**
     * Aprobar o rechazar (solo admin)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);
        $user = Auth::user();

        $nuevoEstado = $request->status;

        // Validaciones según rol
        if ($user->hasRole('asistente') && !in_array($nuevoEstado, ['borrador', 'revision'])) {
            return back()->with('error', 'Como asistente solo puedes cambiar a Borrador o En Revisión.');
        }

        if ($user->hasRole('admin') && !in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
            return back()->with('error', 'Como administrador solo puedes Aprobar o Rechazar.');
        }

        $request->validate([
            'status' => ['required', Rule::in(['borrador', 'revision', 'aprobado', 'rechazado'])]
        ]);

        // Asignar estado
        $reporte->status = $nuevoEstado;

        // Guardar quién revisa
        if (in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
            $reporte->revisado_por = $user->id;
        } else {
            $reporte->revisado_por = null;
        }

        $reporte->save();

        return back()->with('success', 'Estado del reporte de trabajo actualizado correctamente.');
    }
}
