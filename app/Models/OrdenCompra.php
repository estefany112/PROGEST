<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
