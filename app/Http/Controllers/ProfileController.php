<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Bitacora;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
       $user = $request->user();
        $oldName = $user->name;
        $oldEmail = $user->email;

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Registrar en bitácora solo si cambió algo
        if ($oldName !== $user->name || $oldEmail !== $user->email) {
            Bitacora::create([
                'usuario' => $user->name,
                'accion' => 'Actualización',
                'detalle' => sprintf(
                    'El usuario actualizó su perfil. [Antes: %s, %s] [Ahora: %s, %s]',
                    $oldName,
                    $oldEmail,
                    $user->name,
                    $user->email
                ),
                'modulo' => 'Perfil de Usuario',
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Registrar eliminación de cuenta
        Bitacora::create([
            'usuario' => $user->name,
            'accion' => 'Eliminación',
            'detalle' => 'El usuario eliminó su propia cuenta del sistema.',
            'modulo' => 'Perfil de Usuario',
        ]);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
