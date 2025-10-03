<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Factura extends Model
{
    protected $fillable = [
        'orden_compra_id', 
        'cliente_id', 
        'numero_factura', 
        'fecha_emision', 
        'monto_total', 
        'archivo_pdf_path',
        'creada_por'
    ];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    public function contraseñaPago()
    {
        return $this->hasOne(ContrasenaPago::class);
    }

    public function getArchivoPdfUrlAttribute()
    {
        return $this->archivo_pdf_path ? Storage::url($this->archivo_pdf_path) : null;
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }
}

