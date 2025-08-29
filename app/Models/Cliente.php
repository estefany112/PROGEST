<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['nombre','nit','direccion'];

    public function cotizaciones(){
         return $this->hasMany(\App\Models\Cotizacion::class, 'cliente_id');
    }
}
