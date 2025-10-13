<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContrasenaPago;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;

class ContrasenaPagoController extends Controller
{
    /**
     * Listado de contraseñas según rol.
     */
    public function index()
    {
        $user = Auth::user();

        $contrasenas = ContrasenaPago::with('factura.ordenCompra.cotizacion.cliente')
            ->visiblesPara($user)
            ->latest()
            ->paginate(10);

        return view('contrasenas.index', compact('contrasenas'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        $facturas = Factura::with('ordenCompra.cotizacion.cliente')
            ->latest()
            ->get();

        return view('contrasenas.create', compact('facturas'));
    }

    /**
     * Guardar nueva contraseña en estado BORRADOR.
     */
    public function store(Request $request)
    {
        $request->validate([
            'factura_id'       => 'required|exists:facturas,id',
            'codigo'           => 'required|string|max:255|unique:contrasenas_pago,codigo',
            'fecha_documento'  => 'required|date',
            'fecha_aprox'      => 'nullable|date|after_or_equal:fecha_documento',
            'archivo'          => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $data = $request->only(['factura_id', 'codigo', 'fecha_documento', 'fecha_aprox']);
        $data['creada_por'] = Auth::id();
        $data['status'] = 'borrador';

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('contrasenas', 'public');
        }

        ContrasenaPago::create($data);

        return redirect()->route('contrasenas.index')
            ->with('success', 'Contraseña de pago creada como borrador.');
    }

    /**
     * Mostrar detalle de la contraseña.
     */
    public function show($id)
    {
        $contrasena = ContrasenaPago::with('factura.ordenCompra.cotizacion.cliente')->findOrFail($id);
        return view('contrasenas.show', compact('contrasena'));
    }

    /**
     * Editar (solo si está en borrador).
     */
    public function edit($id)
    {
        $contrasena = ContrasenaPago::findOrFail($id);

        if ($contrasena->status !== 'borrador' && Auth::user()->hasRole('asistente')) {
            return redirect()->route('contrasenas.index')
                ->with('error', 'Solo puedes editar contraseñas en estado borrador.');
        }

        $facturas = Factura::with('ordenCompra.cotizacion.cliente')->get();
        return view('contrasenas.edit', compact('contrasena', 'facturas'));
    }

    /**
     * Actualizar datos de la contraseña.
     */
    public function update(Request $request, $id)
    {
        $contrasena = ContrasenaPago::findOrFail($id);

        $request->validate([
            'factura_id'       => 'required|exists:facturas,id',
            'codigo'           => 'required|string|max:255|unique:contrasenas_pago,codigo,' . $contrasena->id,
            'fecha_documento'  => 'required|date',
            'fecha_aprox'      => 'nullable|date|after_or_equal:fecha_documento',
            'archivo'          => 'nullable|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $data = $request->only(['factura_id', 'codigo', 'fecha_documento', 'fecha_aprox']);

        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('contrasenas', 'public');
        }

        $contrasena->update($data);

        return redirect()->route('contrasenas.index')
            ->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Enviar contraseña a revisión (solo asistente).
     */
    public function enviarRevision($id)
    {
        $contrasena = ContrasenaPago::findOrFail($id);

        if ($contrasena->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar contraseñas en borrador.');
        }

        $contrasena->update(['status' => 'revision']);

        return back()->with('success', 'Contraseña enviada a revisión.');
    }

    /**
     * Cambiar estado (solo admin o asistente según permisos).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $contrasena = ContrasenaPago::findOrFail($id);
        $user = Auth::user();
        $nuevoEstado = $request->input('status');

        $request->validate([
            'status' => 'required|in:borrador,revision,aprobado,rechazado'
        ]);

        // Asistente → solo puede mover entre borrador y revisión
        if ($user->hasRole('asistente')) {
            if (!in_array($nuevoEstado, ['borrador', 'revision'])) {
                return back()->with('error', 'No tienes permiso para cambiar a este estado.');
            }
        }

        // Admin → solo puede aprobar o rechazar
        if ($user->hasRole('admin')) {
            if (!in_array($nuevoEstado, ['aprobado', 'rechazado'])) {
                return back()->with('error', 'Solo puedes aprobar o rechazar contraseñas.');
            }

            // Registrar quién revisó
            $contrasena->revisado_por = $user->id;
        }

        $contrasena->status = $nuevoEstado;
        $contrasena->save();

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Eliminar contraseña.
     */
    public function destroy($id)
    {
        $contrasena = ContrasenaPago::findOrFail($id);
        $contrasena->delete();

        return redirect()->route('contrasenas.index')
            ->with('success', 'Contraseña eliminada correctamente.');
    }
}
