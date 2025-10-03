<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReporteTrabajo extends Model
{
    protected $fillable = ['orden_compra_id', 'archivo', 'creada_por'];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function getArchivoUrlAttribute()
    {
        return $this->archivo ? Storage::url($this->archivo) : null;
    }

    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }
}
