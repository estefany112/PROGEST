<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cotizacion;
use App\Models\User;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'ordenes_compra';

    protected $fillable = [
        'cotizacion_id',
        'creada_por',
        'numero_oc',
        'fecha',
        'monto_total',
        'archivo_oc_path',
        'creada_por',
        'status',
        'revisado_por',
    ];

    // 👇 Relación con Cotizacion (cada orden pertenece a una cotización)
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }
    
    public function ordenCompra()
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function reportes()
    {
        return $this->hasMany(ReporteTrabajo::class);
    }

    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? Storage::url($this->archivo) : null;
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * ===========================
     * MÉTODOS DE ESTADO
     * ===========================
     */

    public function isBorrador()
    {
        return $this->status === 'borrador';
    }

    public function isEnRevision()
    {
        return $this->status === 'revision';
    }

    public function isAprobado()
    {
        return $this->status === 'aprobado';
    }

    public function isRechazado()
    {
        return $this->status === 'rechazado';
    }

    /**
     * ===========================
     * SCOPES (para filtrar por rol o estado)
     * ===========================
     */

    public function scopeVisiblesPara($query, $user)
    {
        if ($user->role === 'asistente') {
            // Solo ve las suyas
            return $query->where('creada_por', $user->id);
        }

        if ($user->role === 'admin') {
            // Solo ve las que están en revisión o aprobadas
            return $query->whereIn('status', ['revision', 'aprobado']);
        }

        return $query;
    }
}
