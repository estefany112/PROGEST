<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
    
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
   $request->authenticate();

    $user = Auth::user();

    // Validar estado y rol, pero sin cerrar sesión
    if ($user->estado !== 'activo' || $user->getRoleNames()->isEmpty()) {
        // Guarda la razón y redirige a vista de bloqueo
        $mensaje = match ($user->estado) {
            'pendiente' => 'Tu cuenta está pendiente de aprobación por el administrador.',
            'rechazado' => 'Tu cuenta fue rechazada. Contacta al administrador.',
            default => 'Acceso denegado. No tienes rol asignado.',
        };

        return redirect()->route('acceso.denegado')->with('mensaje', $mensaje);
    }

    // Si todo está bien, regenerar sesión y redirigir a su dashboard
    $request->session()->regenerate();

    $rol = $user->getRoleNames()->first();
    return redirect()->route($rol . '.dashboard');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
