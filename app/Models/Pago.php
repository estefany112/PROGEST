<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'factura_id',
        'fecha_pago',
        'archivo',
        'creada_por',
        'revisado_por',
        'status',
    ];

    /**
     * ===========================
     * RELACIONES
     * ===========================
     */

    // Relación con factura
    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    // Usuario que creó el pago
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    // Usuario que revisó (solo admin)
    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * ===========================
     * ACCESOR PARA ARCHIVO
     * ===========================
     */
    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? Storage::url($this->archivo) : null;
    }

    /**
     * ===========================
     * SCOPES (Visibilidad por rol)
     * ===========================
     */
    public function scopeVisiblesPara($query, $user)
    {
        if ($user->hasRole('asistente')) {
            return $query->where('creada_por', $user->id);
        }
        return $query;
    }

    /**
     * ===========================
     * MÉTODOS DE ESTADO
     * ===========================
     */
    public function isBorrador()  { return $this->status === 'borrador'; }
    public function isEnRevision(){ return $this->status === 'revision'; }
    public function isAprobado()  { return $this->status === 'aprobado'; }
    public function isRechazado() { return $this->status === 'rechazado'; }
}
