<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Factura;
use App\Models\ContrasenaPago;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    /**
     * Listado de pagos según el rol del usuario
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');

        $pagos = Pago::with('factura.ordenCompra.cotizacion.cliente')
        ->when($user->hasRole('asistente'), function ($query) use ($user) {
            // Solo muestra los pagos creados por el asistente autenticado
            $query->where('creada_por', $user->id);
        })
        ->when($status, function ($query) use ($status) {
            // Aplica el filtro si se seleccionó un estado
            $query->where('status', $status);
        })
        ->latest()
        ->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $facturas = Factura::with('ordenCompra.cotizacion.cliente')->get();
        $contrasenas = ContrasenaPago::with('factura')->get();
        return view('pagos.create', compact('facturas', 'contrasenas'));
    }

    /**
     * Guardar nuevo pago (por defecto en estado "pendiente")
     */
    public function store(Request $request)
    {
        $request->validate([
            'factura_id'  => 'required|exists:facturas,id',
            'contrasena_id'  => 'nullable|exists:contrasenas_pago,id',
            'fecha_pago'  => 'required|date',
            'archivo'     => 'nullable|file|mimes:pdf,jpg,png|max:10240'
        ]);

        $data = $request->only(['factura_id', 'contrasena_id','fecha_pago']);
        $data['creada_por'] = Auth::id();
        $data['status'] = 'pendiente';

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('pagos', 'public');
        }

        Pago::create($data);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado como pendiente.');
    }

    /**
     * Mostrar detalle
     */
    public function show($id)
    {
        $pago = Pago::with('factura.ordenCompra.cotizacion.cliente')->findOrFail($id);
        return view('pagos.show', compact('pago'));
    }

    /**
     * Editar pago (solo si está pendiente)
     */
    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $facturas = Factura::with('ordenCompra.cotizacion.cliente')->get();
        $contrasenas = ContrasenaPago::with('factura')->get();

        return view('pagos.edit', compact('pago', 'facturas', 'contrasenas'));
    }

    /**
     * Actualizar datos del pago
     */
    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'factura_id'  => 'required|exists:facturas,id',
            'contrasena_id' => 'nullable|exists:contrasenas_pago,id',
            'fecha_pago'  => 'required|date',
            'archivo'     => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $data = $request->only(['factura_id', 'contrasena_id', 'fecha_pago']);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('pagos', 'public');
        }

        $pago->update($data);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Cambiar estado (solo admin)
     */
    public function cambiarEstado(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'status' => 'required|in:pendiente,pagada'
        ]);

        // Solo admin puede marcar como pagada
        if ($user->hasRole('admin')) {
            $pago->status = $request->status;
            $pago->revisado_por = $user->id;
            $pago->save();

            return back()->with('success', 'Estado del pago actualizado.');
        }

        return back()->with('error', 'No tienes permisos para cambiar el estado del pago.');
    }

    /**
     * Eliminar pago
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }
}
