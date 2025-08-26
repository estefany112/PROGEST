<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCotizacion extends Model
{
    use HasFactory;

    protected $table = 'items_cotizacion';

    protected $fillable = [
        'cotizacion_id',
        'cantidad',
        'unidad_medida',
        'descripcion',
        'precio_unitario',
        'total',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con la cotización
     */
    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    /**
     * Calcular el total del item
     */
    public function calcularTotal(): void
    {
        $this->total = $this->cantidad * $this->precio_unitario;
    }

    /**
     * Boot del modelo para calcular total automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->calcularTotal();
        });
    }
} 