<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
   protected $fillable = ['factura_id', 'estado', 'fecha_pago', 'creada_por'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }
}

