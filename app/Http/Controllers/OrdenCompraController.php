<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCompra;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Auth;

class OrdenCompraController extends Controller
{
    /**
     * Mostrar listado según el rol del usuario
     */
    public function index()
    {
        $user = Auth::user();

        // Aplica el scope del modelo (visibles para cada rol)
        $ordenes = OrdenCompra::with('cotizacion')
            ->visiblesPara($user)
            ->latest()
            ->paginate(10);

        return view('ordenes_compra.index', compact('ordenes'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create($cotizacionId = null)
    {
        $cotizaciones = Cotizacion::where('estado', 'aprobada')->get();
        $cotizacion = $cotizacionId ? Cotizacion::findOrFail($cotizacionId) : null;

        return view('ordenes_compra.create', compact('cotizaciones', 'cotizacion'));
    }

    /**
     * Guardar nueva orden en estado BORRADOR
     */
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
        $data['status'] = 'borrador';

        if ($request->hasFile('archivo_oc_path')) {
            $data['archivo_oc_path'] = $request->file('archivo_oc_path')->store('ordenes', 'public');
        }

        OrdenCompra::create($data);

        return redirect()->route('ordenes-compra.index')
            ->with('success', 'Orden de compra creada como borrador.');
    }

    /**
     * Mostrar detalle
     */
    public function show($id)
    {
        $orden = OrdenCompra::with('cotizacion')->findOrFail($id);
        return view('ordenes_compra.show', compact('orden'));
    }

    /**
     * Editar orden (solo si es borrador)
     */
    public function edit($id)
    {
        $orden = OrdenCompra::findOrFail($id);

        if ($orden->status !== 'borrador' && Auth::user()->role === 'asistente') {
            return redirect()->route('ordenes-compra.index')
                ->with('error', 'Solo puedes editar órdenes en estado borrador.');
        }

        $cotizaciones = Cotizacion::where('estado', 'aprobada')->get();
        return view('ordenes_compra.edit', compact('orden', 'cotizaciones'));
    }

    /**
     * Actualizar datos
     */
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

    /**
     * Enviar orden a revisión (solo asistente)
     */
    public function enviarRevision($id)
    {
        $orden = OrdenCompra::findOrFail($id);

        if ($orden->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar órdenes en borrador.');
        }

        $orden->update(['status' => 'revision']);

        return back()->with('success', 'Orden enviada a revisión.');
    }

    /**
     * Aprobar o rechazar (solo admin)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $user = Auth::user();
        $nuevoEstado = $request->input('status');

        // Validar que el estado sea uno válido
        $request->validate([
            'status' => 'required|in:borrador,revision,aprobado,rechazado'
        ]);

        // 🔹 Si es asistente
        if ($user->role === 'asistente') {
            // Solo puede mover entre borrador y revisión
            if (!in_array($nuevoEstado, ['borrador', 'revision'])) {
                return back()->with('error', 'No tienes permiso para cambiar a este estado.');
            }
        }

        // Si es administrador
        if ($user->role === 'admin') {
            // Solo puede aprobar o rechazar
            if (!in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
                return back()->with('error', 'Solo puedes aprobar o rechazar órdenes.');
            }
        }

        // Si llega aquí, tiene permiso
        $orden->update(['status' => $nuevoEstado]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }


    /**
     * Eliminar orden
     */
    public function destroy($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $orden->delete();

        return redirect()->route('ordenes-compra.index')
            ->with('success', 'Orden de compra eliminada correctamente.');
    }
}
