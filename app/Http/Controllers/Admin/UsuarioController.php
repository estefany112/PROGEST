<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class UsuarioController extends Controller
{
    // Mostrar usuarios con estado pendiente o rechazado
    public function pendientes()
    {
        $usuariosPendientes = User::whereIn('estado', ['pendiente', 'rechazado'])->get();
        return view('admin.usuarios_pendientes', compact('usuariosPendientes'));
    }

    // Aprobar usuario: cambia estado a activo y asigna rol
    public function aprobar(Request $request, $id)
    {
        $request->validate([
            'rol' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->estado = 'activo';
        $user->save();

        $user->syncRoles([$request->rol]);

        return redirect()->route('admin.usuarios.pendientes')->with('success', 'Usuario aprobado correctamente.');
    }

    // Rechazar usuario: cambia estado a rechazado y elimina roles
    public function rechazar($id)
    {
        $user = User::findOrFail($id);
        $user->estado = 'rechazado';
        $user->syncRoles([]); // Elimina todos los roles
        $user->save();

        return redirect()->route('admin.usuarios.pendientes')->with('success', 'Usuario rechazado correctamente.');
    }
}

