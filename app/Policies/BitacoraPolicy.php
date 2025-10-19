<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bitacora;

class BitacoraPolicy
{
    /**
     * Determina si el usuario puede ver cualquier registro de bitácora.
     */
    public function viewAny(User $user)
    {
        // Solo los administradores pueden ver la bitácora
        return $user->hasRole('admin'); // Esto verifica si el usuario tiene el rol de 'admin'
    }
}
