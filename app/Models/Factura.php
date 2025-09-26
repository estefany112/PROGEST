<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Factura extends Model
{
    protected $fillable = [
        'orden_compra_id', 
        'cliente_id', 
        'numero_factura', 
        'fecha_emision', 
        'monto_total', 
        'archivo_pdf_path'
    ];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function contraseñaPago()
    {
        return $this->hasOne(ContraseñaPago::class);
    }

    public function getArchivoPdfUrlAttribute()
    {
        return $this->archivo_pdf_path ? Storage::url($this->archivo_pdf_path) : null;
    }
}

