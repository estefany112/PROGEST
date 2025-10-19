<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bitacora;
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

        Bitacora::create([
            'usuario' => auth()->user()->name,
            'accion' => 'Consulta',
            'detalle' => 'El administrador consultó la lista de usuarios pendientes o rechazados',
            'modulo' => 'Usuarios',
        ]);

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

        Bitacora::create([
            'usuario' => auth()->user()->name,
            'accion' => 'Aprobación',
            'detalle' => 'Se aprobó al usuario "' . $user->name . '" con rol ' . $request->rol,
            'modulo' => 'Usuarios',
        ]);

        return redirect()->route('admin.usuarios.pendientes')->with('success', 'Usuario aprobado correctamente.');
    }

    // Rechazar usuario: cambia estado a rechazado y elimina roles
    public function rechazar($id)
    {
        $user = User::findOrFail($id);
        $user->estado = 'rechazado';
        $user->syncRoles([]); // Elimina todos los roles
        $user->save();

        Bitacora::create([
            'usuario' => auth()->user()->name,
            'accion' => 'Rechazo',
            'detalle' => 'Se rechazó al usuario "' . $user->name . '"',
            'modulo' => 'Usuarios',
        ]);

        return redirect()->route('admin.usuarios.pendientes')->with('success', 'Usuario rechazado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = ['admin', 'asistente']; 
        $estados = ['pendiente', 'activo', 'rechazado'];

        Bitacora::create([
            'usuario' => auth()->user()->name,
            'accion' => 'Consulta',
            'detalle' => 'El administrador accedió al formulario de edición del usuario "' . $usuario->name . '"',
            'modulo' => 'Usuarios',
        ]);

        return view('admin.usuarios_edit', compact('usuario', 'roles', 'estados'));
    }

    public function update(Request $request, $id)
    {
        if ($id == 1) {
            return back()->with('error', 'No puedes modificar al usuario principal.');
        }

        $user = User::findOrFail($id);

        // Permite que solo se mande uno de los dos campos
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $id,
            'tipo'   => 'nullable|in:admin,asistente',
            'estado' => 'nullable|in:pendiente,activo,rechazado',
        ]);

        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldTipo = $user->tipo;
        $oldEstado = $user->estado;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
        ]);

        $user->syncRoles([$request->tipo]);

        Bitacora::create([
            'usuario' => auth()->user()->name,
            'accion' => 'Actualización',
            'detalle' => sprintf(
                'Se actualizó el usuario "%s". [Antes: %s, %s, %s] [Ahora: %s, %s, %s]',
                $user->name,
                $oldName,
                $oldEmail,
                $oldEstado,
                $request->name,
                $request->email,
                $request->estado
            ),
            'modulo' => 'Usuarios',
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente y registrado en bitácora.');   
        }

        public function destroy($id)
        {
            $user = User::findOrFail($id);

            if ($user->id == 1) {
                return back()->with('error', 'No puedes eliminar al usuario principal.');
            }

            Bitacora::create([
                'usuario' => auth()->user()->name,
                'accion' => 'Eliminación',
                'detalle' => 'Se eliminó al usuario "' . $user->name . '"',
                'modulo' => 'Usuarios',
            ]);

            $user->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente.');
        }
    }