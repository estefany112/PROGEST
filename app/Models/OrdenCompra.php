<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

}
