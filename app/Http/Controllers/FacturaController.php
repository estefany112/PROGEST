<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use App\Models\OrdenCompra;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    /**
     * Mostrar listado de facturas según el rol.
     */
    public function index()
    {
        $user = Auth::user();

        $facturas = Factura::with('ordenCompra.cotizacion.cliente')
            ->when($user->hasRole('asistente'), function ($query) use ($user) {
                $query->where('creada_por', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('facturas.index', compact('facturas'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $ordenes = OrdenCompra::with('cotizacion.cliente')
            ->whereIn('status', ['aprobado'])
            ->latest()
            ->get();

        return view('facturas.create', compact('ordenes'));
    }

    /**
     * Guardar nueva factura (inicia como borrador).
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

        $orden = OrdenCompra::with('cotizacion.cliente')->findOrFail($request->orden_compra_id);

        $data = [
            'orden_compra_id' => $request->orden_compra_id,
            'cliente_id'      => $orden->cotizacion->cliente_id ?? null,
            'numero_factura'  => $request->numero_factura,
            'fecha_emision'   => $request->fecha_emision,
            'monto_total'     => $request->monto_total,
            'creada_por'      => Auth::id(),
            'status'          => 'borrador',
        ];

        if ($request->hasFile('archivo_pdf_path')) {
            $data['archivo_pdf_path'] = $request->file('archivo_pdf_path')->store('facturas', 'public');
        }

        Factura::create($data);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura creada como borrador.');
    }

    /**
     * Mostrar detalle.
     */
    public function show($id)
    {
        $factura = Factura::with('ordenCompra.cotizacion.cliente')->findOrFail($id);
        return view('facturas.show', compact('factura'));
    }

    /**
     * Editar factura (solo si está en borrador y es del asistente).
     */
    public function edit($id)
    {
        $factura = Factura::findOrFail($id);
        $user = Auth::user();

        if ($factura->status !== 'borrador' && $user->hasRole('asistente')) {
            return redirect()->route('facturas.index')
                ->with('error', 'Solo puedes editar facturas en estado borrador.');
        }

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

        // Actualiza cliente según orden
        $orden = OrdenCompra::with('cotizacion.cliente')->findOrFail($request->orden_compra_id);
        $data['cliente_id'] = $orden->cotizacion->cliente_id ?? null;

        $factura->update($data);

        return redirect()->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    /**
     * Enviar factura a revisión (solo asistente).
     */
    public function enviarRevision($id)
    {
        $factura = Factura::findOrFail($id);

        if ($factura->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar facturas en estado borrador.');
        }

        $factura->update(['status' => 'revision']);

        return back()->with('success', 'Factura enviada a revisión.');
    }

    /**
     * Aprobar o rechazar factura (solo admin).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);
        $user = Auth::user();
        $nuevoEstado = $request->input('status');

        $request->validate([
            'status' => 'required|in:borrador,revision,aprobado,rechazado'
        ]);

        // 🔹 Asistente: solo puede mover a revisión
        if ($user->hasRole('asistente')) {
            if (!in_array($nuevoEstado, ['borrador', 'revision'])) {
                return back()->with('error', 'No tienes permiso para cambiar a este estado.');
            }
        }

        // 🔹 Admin: aprobar o rechazar
        if ($user->hasRole('admin')) {
            if (!in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
                return back()->with('error', 'Solo puedes aprobar o rechazar facturas.');
            }

            // ✅ Registrar revisor
            $factura->revisado_por = $user->id;
        }

        $factura->status = $nuevoEstado;
        $factura->save();

        return back()->with('success', 'Estado actualizado correctamente.');
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
