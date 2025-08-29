{{-- resources/views/admin/usuarios_edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 px-6">
  <h1 class="text-2xl font-bold text-white mb-6">Editar Usuario</h1>

  @if(session('error'))
    <div class="mb-4 text-sm text-red-300 bg-red-900/30 border border-red-700 px-3 py-2 rounded">
      {{ session('error') }}
    </div>
  @endif

  <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <label class="block">
      <span class="text-sm text-gray-300">Nombre *</span>
      <input name="name" value="{{ old('name', $usuario->name) }}"
             class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded" required>
      @error('name') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
    </label>

    <label class="block">
      <span class="text-sm text-gray-300">Email *</span>
      <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
             class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded" required>
      @error('email') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
    </label>

    <label class="block">
      <span class="text-sm text-gray-300">Estado *</span>
      <select name="estado"
              class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded" required>
        @foreach(['activo','pendiente','rechazado'] as $e)
          <option value="{{ $e }}" @selected(old('estado', $usuario->estado) === $e)>{{ ucfirst($e) }}</option>
        @endforeach
      </select>
      @error('estado') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
    </label>

    <label class="block">
      <span class="text-sm text-gray-300">Rol *</span>
      <select name="rol"
              class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded" required>
        @foreach($roles as $rol)
          <option value="{{ $rol->name }}"
            @selected( $usuario->roles->pluck('name')->contains($rol->name) )>
            {{ ucfirst($rol->name) }}
          </option>
        @endforeach
      </select>
      @error('rol') <p class="text-sm text-red-400">{{ $message }}</p> @enderror
    </label>

    <div class="flex justify-end gap-3 pt-2">
      <a href="{{ route('usuarios.index') }}" class="px-4 py-2 border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Cancelar</a>
      <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Actualizar</button>
    </div>
  </form>
</div>
@endsection
