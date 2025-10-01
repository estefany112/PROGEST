<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContrasenaPago extends Model
{
   protected $table = 'contrasenas_pago';
   
   protected $fillable = [
        'factura_id',
        'codigo',
        'estado',
        'validada_en',
        'archivo',
        'fecha_aprox',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
