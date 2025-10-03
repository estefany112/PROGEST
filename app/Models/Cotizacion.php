<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'folio',
        'fecha_emision',
       'cliente_id',
        'cliente_nombre',
        'cliente_direccion',
        'cliente_nit',
        'subtotal',
        'iva',
        'total',
        'estado',
        'comentario_rechazo',
        'creada_por',
        'revisada_por',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'subtotal' => 'decimal:2',
        'iva' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el usuario que creó la cotización
     */
    public function creadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por');
    }

    /**
     * Relación con el usuario que revisó la cotización
     */
    public function revisadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisada_por');
    }

    /**
     * Relación con los items de la cotización
     */
    public function items(): HasMany
    {
        return $this->hasMany(ItemCotizacion::class, 'cotizacion_id');
    }

    /**
     * Generar folio automático
     */
    public static function generarFolio(): string
    {
        $ultimaCotizacion = self::whereYear('created_at', now()->year)
            ->orderBy('id', 'desc')
            ->first();

        $numero = $ultimaCotizacion ? intval(substr($ultimaCotizacion->folio, -3)) + 1 : 1;

        return 'COT-' . now()->year . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular totales de la cotización
     */
    public function calcularTotales(): void
    {
        $subtotal = $this->items->sum('total');
        $iva = $subtotal * 0.19; // 19% IVA
        $total = $subtotal + $iva;

        $this->update([
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
        ]);
    }

    /**
     * Verificar si la cotización puede ser enviada a revisión
     */
    public function puedeEnviarARevision(): bool
    {
        return $this->estado === 'borrador' && $this->items->count() > 0;
    }

    /**
     * Verificar si la cotización puede ser aprobada
     */
    public function puedeSerAprobada(): bool
    {
        return $this->estado === 'en_revision';
    }

    /**
     * Verificar si la cotización puede ser rechazada
     */
    public function puedeSerRechazada(): bool
    {
        return $this->estado === 'en_revision';
    }

    /**
     * Obtener el estado en formato legible
     */
    public function getEstadoTextoAttribute(): string
    {
        return match($this->estado) {
            'borrador' => 'Borrador',
            'en_revision' => 'En Revisión',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
            default => 'Desconocido'
        };
    }

    /**
     * Obtener la clase CSS para el estado
     */
    public function getEstadoClaseAttribute(): string
    {
        return match($this->estado) {
            'borrador' => 'badge bg-secondary',
            'en_revision' => 'badge bg-warning',
            'aprobada' => 'badge bg-success',
            'rechazada' => 'badge bg-danger',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Relación con el cliente
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }

    // Función para que no muestre las cotizaciones en borrador
    public function scopeVisiblePara($query, $user)
    {
        if ($user->tipo === 'asistente') {
            return $query->where('creada_por', $user->id);
        } elseif ($user->tipo === 'admin') {
            return $query->where('estado', '!=', 'borrador');
        }

        return $query;
    }

} 