<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$tipos Los tipos permitidos (admin, asistente)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$tipos)
    {
        $usuario = Auth::user();

        if (!$usuario || !in_array($usuario->tipo, $tipos)) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
    
}
