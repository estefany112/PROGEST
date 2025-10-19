<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario',  // Nombre del usuario que realiza la acción
        'accion',   // Tipo de acción (crear, modificar, eliminar)
        'detalle',  // Detalles sobre lo que ocurrió
        'modulo',   // El módulo donde ocurrió la acción
    ];
}
