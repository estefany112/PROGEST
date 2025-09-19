<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class UsuarioController extends Controller
{
    public function index(Request $request)
{
   $q = $request->input('q');
    $estado = $request->input('estado');
    $rol = $request->input('rol');

    $query = User::query();

    if ($q) {
        $query->where(function($sub) use ($q) {
            $sub->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%");
        });
    }

    if ($estado) {
        $query->where('estado', $estado);
    }

    if ($rol) {
        $query->whereHas('roles', function($sub) use ($rol) {
            $sub->where('name', $rol);
        });
    }

    $usuarios = $query->latest()->paginate(10);

    // estados y roles
    $estados = ['pendiente', 'activo', 'rechazado'];
    $roles = ['admin', 'asistente'];

    return view('admin.usuarios_index', compact('usuarios', 'q', 'estado', 'rol', 'estados', 'roles'));
}

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

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios_edit', compact('usuario'));
    }

     public function update(Request $request, $id)
    {
        if ($id == 1) {
        return back()->with('error', 'No puedes modificar al usuario principal.');
    }
        $user = User::findOrFail($id);

        // Validar que el rol sea válido
        if ($request->filled('tipo') && in_array($request->tipo, ['admin', 'asistente'])) {
            $user->syncRoles([$request->tipo]);
            $user->tipo = $request->tipo; // si usas la columna 'tipo' en users
        }

        if ($request->filled('estado') && in_array($request->estado, ['pendiente', 'activo', 'rechazado'])) {
            $user->estado = $request->estado;
        }

        $user->save();

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

}

