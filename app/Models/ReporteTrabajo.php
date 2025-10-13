<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReporteTrabajo extends Model
{
    protected $fillable = ['orden_compra_id', 'archivo', 'creada_por', 'status', 'revisado_por'];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? Storage::url($this->archivo) : null;
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    public function enviarRevision($id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        if ($reporte->status !== 'borrador') {
            return back()->with('error', 'Solo se pueden enviar reportes en borrador.');
        }

        $reporte->update(['status' => 'revision']);

        return back()->with('success', 'Reporte enviado a revisión.');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $reporte = ReporteTrabajo::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'No tienes permiso para cambiar estados.');
        }

        $request->validate([
            'status' => 'required|in:aprobado,rechazado'
        ]);

        $reporte->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function scopeVisiblesPara($query, $user)
    {
        if ($user->role === 'asistente') {
            return $query->where('creada_por', $user->id);
        }

        return $query;
    }

}
