<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReporteTrabajo extends Model
{
    protected $fillable = [
        'orden_compra_id',
        'archivo',
        'creada_por',
        'status',
        'revisado_por',
    ];

    // Relación con Orden de Compra
    public function ordenCompra(): BelongsTo
    {
        return $this->belongsTo(OrdenCompra::class, 'orden_compra_id');
    }

    // URL del archivo
    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? Storage::url($this->archivo) : null;
    }

    // Relación con usuario creador
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    // Relación con usuario revisor (admin)
    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * ===========================
     *   MÉTODOS DE ESTADO
     * ===========================
     */

    // Enviar a revisión (solo asistente)
    public function enviarRevision($id)
    {
        $reporte = self::findOrFail($id);

        if ($reporte->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar reportes en borrador.');
        }

        $reporte->update(['status' => 'revision']);

        return back()->with('success', 'Reporte enviado a revisión.');
    }

    // Aprobar o rechazar (solo admin)
    public function cambiarEstado(Request $request, $id)
    {
        $reporte = self::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'status' => 'required|in:aprobado,rechazado'
        ]);

        // Solo admin puede aprobar o rechazar
        if ($user->hasRole('admin')) {
            $reporte->revisado_por = $user->id;
            $reporte->status = $request->status;
            $reporte->save();

            return back()->with('success', 'Estado actualizado correctamente.');
        }

        return back()->with('error', 'No tienes permiso para cambiar estados.');
    }

    /**
     * ===========================
     *   SCOPES
     * ===========================
     */

    // Filtrar según el rol
    public function scopeVisiblesPara($query, $user)
    {
        if ($user->hasRole('asistente')) {
            return $query->where('creada_por', $user->id);
        }

        return $query;
    }
}
