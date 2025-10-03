<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'fecha_documento',
        'creada_por',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

}
