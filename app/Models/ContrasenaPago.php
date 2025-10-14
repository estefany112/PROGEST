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
        'revisado_por', 
        'status', '
        contrasena_id'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    public function revisadoPor() { return $this->belongsTo(User::class, 'revisado_por'); }

    public function scopeVisiblesPara($query, $user)
    {
        if ($user->hasRole('asistente')) {
            return $query->where('creada_por', $user->id);
        }
        return $query;
    }

}
