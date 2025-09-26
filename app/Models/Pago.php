<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
   protected $fillable = ['factura_id', 'estado', 'fecha_pago', 'metodo_pago'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}

