<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    // === LISTADO (con búsqueda básica) ===
    public function index(Request $request)
    {
        $q      = trim($request->get('q',''));
        $estado = $request->get('estado','');   // activo | pendiente | rechazado | (vacío = todos)
        $rol    = $request->get('rol','');      // nombre del rol

        $usuarios = User::query()
            ->when($q, fn($qry)=> $qry->where(fn($w)=> $w
                ->where('name','like',"%{$q}%")
                ->orWhere('email','like',"%{$q}%")
                ->orWhere('estado','like',"%{$q}%")
            ))
            ->when($estado !== '', fn($qry)=> $qry->where('estado',$estado))
            ->when($rol !== '', fn($qry)=> $qry->whereHas('roles', fn($r)=> $r->where('name',$rol)))
            ->with('roles')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $roles = Role::pluck('name'); // para el filtro de roles

        return view('admin.usuarios_index', compact('usuarios','q','estado','rol','roles'));
    }

    // === EDITAR ===
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles   = Role::orderBy('name')->get();

        return view('admin.usuarios_edit', compact('usuario','roles'));
    }

    // === ACTUALIZAR ===
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $data = $request->validate([
            'name'   => ['required','string','max:255'],
            'email'  => ['required','email','max:255', Rule::unique('users','email')->ignore($usuario->id)],
            'rol'    => ['required','string','exists:roles,name'],
            'estado' => ['required', Rule::in(['activo','pendiente','rechazado'])],
        ]);

        // Evitar que el admin se auto-bloquee
        if (Auth::id() === $usuario->id && $data['estado'] !== 'activo') {
            return back()->with('error','No puedes cambiar tu propio estado a pendiente/rechazado.')->withInput();
        }

        $usuario->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'estado' => $data['estado'],
        ]);

        $usuario->syncRoles([$data['rol']]);

        return redirect()->route('usuarios.index')->with('success','Usuario actualizado correctamente.');
    }

    // === ELIMINAR ===
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // No auto-eliminarse
        if (Auth::id() === $usuario->id) {
            return back()->with('error','No puedes eliminar tu propio usuario.');
        }

        // Evitar borrar al último admin
        if ($usuario->hasRole('admin')) {
            $adminsRestantes = User::role('admin')->where('id','!=',$usuario->id)->count();
            if ($adminsRestantes === 0) {
                return back()->with('error','No puedes eliminar al último administrador.');
            }
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success','Usuario eliminado correctamente.');
    }


    // ===== Estas 3 acciones las mantengo por si usas el flujo pendiente/rechazado =====
    public function pendientes()
    {
        $usuariosPendientes = User::whereIn('estado', ['pendiente', 'rechazado'])
            ->with('roles')
            ->orderBy('created_at','desc')
            ->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.usuarios_pendientes', compact('usuariosPendientes','roles'));
    }
   
    public function aprobar(Request $request, $id)
    {
        $request->validate([
            'rol' => ['required','string','exists:roles,name'],
        ]);

        $usuario = User::findOrFail($id);

        if (Auth::id() === $usuario->id && $usuario->estado !== 'activo') {
            return back()->with('error','No puedes cambiar tu propio estado.')->withInput();
        }

        $usuario->estado = 'activo';
        $usuario->save();

        $usuario->syncRoles([$request->rol]);

        return redirect()->route('admin.usuarios.pendientes')->with('success','Usuario aprobado correctamente.');
    }

    public function rechazar($id)
    {
        $usuario = User::findOrFail($id);

        if (Auth::id() === $usuario->id) {
            return back()->with('error','No puedes rechazar tu propio usuario.');
        }

        $usuario->estado = 'rechazado';
        $usuario->syncRoles([]);
        $usuario->save();

        return redirect()->route('admin.usuarios.pendientes')->with('success','Usuario rechazado correctamente.');
    }
}