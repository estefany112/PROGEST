<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContraseñaPago extends Model
{
   protected $fillable = ['factura_id', 'contraseña', 'validada'];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
