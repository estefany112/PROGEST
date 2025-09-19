<?php

namespace App\Policies;

use App\Models\Cotizacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CotizacionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->tipo, ['admin', 'asistente']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cotizacion $cotizacion): bool
    {
        // Admin puede ver todas las cotizaciones
        if ($user->tipo === 'admin') {
            return true;
        }

        // Asistente solo puede ver sus propias cotizaciones
        return $user->tipo === 'asistente' && $cotizacion->creada_por === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tipo === 'asistente';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cotizacion $cotizacion): bool
    {
        // El admin puede cambiar el estado de cualquier cotización
        if ($user->tipo === 'admin') {
            return true;
        }
        // Solo el asistente que creó la cotización puede editarla si está en borrador
        return $user->tipo === 'asistente' && 
               $cotizacion->creada_por === $user->id && 
               in_array($cotizacion->estado, ['borrador', 'en_revision']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cotizacion $cotizacion): bool
    {
        // El admin puede eliminar cualquier cotización
        if ($user->tipo === 'admin') {
            return true;
        }
        // Solo el asistente que creó la cotización puede eliminarla si está en borrador
        return $user->tipo === 'asistente' && 
               $cotizacion->creada_por === $user->id && 
               $cotizacion->estado === 'borrador';
    }

    /**
     * Determine whether the user can send the model to review.
     */
    public function enviarRevision(User $user, Cotizacion $cotizacion): bool
    {
        // Solo el asistente que creó la cotización puede enviarla a revisión
        return $user->tipo === 'asistente' && 
               $cotizacion->creada_por === $user->id && 
               $cotizacion->estado === 'borrador';
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function aprobar(User $user, Cotizacion $cotizacion): bool
    {
        // Solo el admin puede aprobar cotizaciones
        return $user->tipo === 'admin' && $cotizacion->estado === 'en_revision';
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function rechazar(User $user, Cotizacion $cotizacion): bool
    {
        // Solo el admin puede rechazar cotizaciones
        return $user->tipo === 'admin' && $cotizacion->estado === 'en_revision';
    }
} 