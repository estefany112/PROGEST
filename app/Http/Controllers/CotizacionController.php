<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\ItemCotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizacionController extends Controller
{
    /**
     * Mostrar listado de cotizaciones
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->tipo === 'admin') {
            // Admin ve todas las cotizaciones
            $cotizaciones = Cotizacion::with(['creadaPor', 'revisadaPor'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Asistente ve solo sus cotizaciones
            $cotizaciones = Cotizacion::with(['creadaPor', 'revisadaPor'])
                ->where('creada_por', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('cotizaciones.index', compact('cotizaciones'));
    }

    /**
     * Mostrar formulario para crear cotización
     */
    public function create()
    {
        return view('cotizaciones.create');
    }

    /**
     * Guardar nueva cotización
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => ['required','exists:clientes,id'],
            'fecha_emision' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.unidad_medida' => 'required|string|max:50',
            'items.*.descripcion' => 'required|string',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cliente = Cliente::findOrFail($request->cliente_id);
            // Crear cotización
            $cotizacion = Cotizacion::create([
                'folio' => Cotizacion::generarFolio(),
                'fecha_emision' => $request->fecha_emision,
                'cliente_id'       => $cliente->id,
                'cliente_nombre' => $cliente->nombre,
                'cliente_direccion' => $cliente->direccion,
                'cliente_nit' => $cliente->nit,
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'estado' => 'borrador',
                'creada_por' => Auth::id(),
            ]);

            // Crear items
            foreach ($request->items as $itemData) {
                ItemCotizacion::create([
                    'cotizacion_id' => $cotizacion->id,
                    'cantidad' => $itemData['cantidad'],
                    'unidad_medida' => $itemData['unidad_medida'],
                    'descripcion' => $itemData['descripcion'],
                    'precio_unitario' => $itemData['precio_unitario'],
                ]);
            }

            // Calcular totales
            $cotizacion->calcularTotales();

            DB::commit();

            return redirect()->route('cotizaciones.show', $cotizacion)
                ->with('success', 'Cotización creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear la cotización: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar cotización específica
     */
    public function show(Cotizacion $cotizacion)
    {
        $this->authorize('view', $cotizacion);
        
        $cotizacion->load(['items', 'creadaPor', 'revisadaPor']);
        
        return view('cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Mostrar formulario para editar cotización
     */
    public function edit(Cotizacion $cotizacion)
    {
        // Si la cotización ya fue aprobada, redirigir a una vista especial
        if ($cotizacion->estado === 'aprobada') {
            return redirect()->route('cotizaciones.index')
                ->with('error', 'Esta cotización ya fue aprobada y no puede editarse.');
        }
        
        $this->authorize('update', $cotizacion);

        $cotizacion->load('items');
        
        return view('cotizaciones.edit', compact('cotizacion'));
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        $this->authorize('update', $cotizacion);

        $request->validate([
            'cliente_id' => ['required','exists:clientes,id'],
            'fecha_emision' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.unidad_medida' => 'required|string|max:50',
            'items.*.descripcion' => 'required|string',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $cliente = Cliente::findOrFail($request->cliente_id);

            // Actualizar datos de la cotización
            $cotizacion->update([
            'fecha_emision'     => $request->fecha_emision,
            'cliente_id'        => $cliente->id,
            'cliente_nombre'    => $cliente->nombre,
            'cliente_direccion' => $cliente->direccion,
            'cliente_nit'       => $cliente->nit,
          ]);

            // Eliminar items existentes
            $cotizacion->items()->delete();

            // Crear nuevos items
            foreach ($request->items as $itemData) {
                ItemCotizacion::create([
                    'cotizacion_id' => $cotizacion->id,
                    'cantidad' => $itemData['cantidad'],
                    'unidad_medida' => $itemData['unidad_medida'],
                    'descripcion' => $itemData['descripcion'],
                    'precio_unitario' => $itemData['precio_unitario'],
                ]);
            }

            // Calcular totales
            $cotizacion->calcularTotales();

            DB::commit();

            return redirect()->route('cotizaciones.show', $cotizacion)
                ->with('success', 'Cotización actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar la cotización: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar cotización
     */
    public function destroy(Cotizacion $cotizacion)
    {
        $this->authorize('delete', $cotizacion);

        try {
            $cotizacion->delete();
            return redirect()->route('cotizaciones.index')
                ->with('success', 'Cotización eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la cotización: ' . $e->getMessage());
        }
    }

    /**
     * Enviar cotización a revisión
     */
    public function enviarRevision(Cotizacion $cotizacion)
    {
        $this->authorize('enviarRevision', $cotizacion);

        if (!$cotizacion->puedeEnviarARevision()) {
            return back()->with('error', 'No se puede enviar esta cotización a revisión.');
        }

        $cotizacion->update(['estado' => 'en_revision']);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización enviada a revisión exitosamente.');
    }

    /**
     * Aprobar cotización
     */
    public function aprobar(Cotizacion $cotizacion)
    {
        $this->authorize('aprobar', $cotizacion);

        if (!$cotizacion->puedeSerAprobada()) {
            return back()->with('error', 'No se puede aprobar esta cotización.');
        }

        $cotizacion->update([
            'estado' => 'aprobada',
            'revisada_por' => Auth::id(),
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización aprobada exitosamente.');
    }

    /**
     * Rechazar cotización
     */
    public function rechazar(Request $request, Cotizacion $cotizacion)
    {
        $this->authorize('rechazar', $cotizacion);

        $request->validate([
            'comentario_rechazo' => 'nullable|string|max:1000',
        ]);

        if (!$cotizacion->puedeSerRechazada()) {
            return back()->with('error', 'No se puede rechazar esta cotización.');
        }

        $cotizacion->update([
            'estado' => 'rechazada',
            'comentario_rechazo' => $request->comentario_rechazo,
            'revisada_por' => Auth::id(),
        ]);

        return redirect()->route('cotizaciones.show', $cotizacion)
            ->with('success', 'Cotización rechazada exitosamente.');
    }

    /**
     * Generar PDF de la cotización
     */
    public function pdf(Cotizacion $cotizacion)
    {
    $this->authorize('view', $cotizacion);
    $cotizacion->load(['items', 'creadaPor', 'revisadaPor']);

    $pdf = Pdf::loadView('cotizaciones.pdf', compact('cotizacion'));
    $filename = 'Cotizacion_' . $cotizacion->folio . '.pdf';
    return $pdf->download($filename);
    }
    /**
     * Cambiar el estado de la cotización (solo admin)
     */
    public function cambiarEstado(Request $request, Cotizacion $cotizacion)
    {
        $this->authorize('update', $cotizacion);

        $request->validate([
            'estado' => [
                'required',
                Rule::in(['borrador', 'en_revision', 'aprobada', 'rechazada'])
            ],
        ]);

        $cotizacion->estado = $request->estado;
        // Si se rechaza, limpiar comentario si no se envía
        if ($request->estado !== 'rechazada') {
            $cotizacion->comentario_rechazo = null;
        }
        // Si se aprueba o rechaza, guardar quién revisó
        if (in_array($request->estado, ['aprobada', 'rechazada'])) {
            $cotizacion->revisada_por = Auth::id();
        } else {
            $cotizacion->revisada_por = null;
        }
        $cotizacion->save();

        return back()->with('success', 'Estado de la cotización actualizado correctamente.');
    }
} 