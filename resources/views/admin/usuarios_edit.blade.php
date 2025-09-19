@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Usuario</h1>

    @if(session('success'))
        <div class="mb-4 text-sm text-green-300 bg-green-900/30 border border-green-700 px-3 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 text-sm text-red-300 bg-red-900/30 border border-red-700 px-3 py-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gray-900 border border-gray-700 rounded-lg p-6">
        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                       class="w-full px-3 py-2 border rounded bg-gray-800 text-white">
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                       class="w-full px-3 py-2 border rounded bg-gray-800 text-white">
            </div>

            {{-- Rol --}}
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Rol</label>
                @if($usuario->id === 1)
                    <input type="text" value="Admin (Protegido)" 
                           class="w-full px-3 py-2 border rounded bg-gray-700 text-gray-400" disabled>
                @else
                    <select name="tipo" class="w-full px-3 py-2 border rounded bg-gray-800 text-white">
                        <option value="">-- Selecciona --</option>
                        <option value="admin" {{ $usuario->tipo === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="asistente" {{ $usuario->tipo === 'asistente' ? 'selected' : '' }}>Asistente</option>
                    </select>
                @endif
            </div>

            {{-- Estado --}}
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Estado</label>
                @if($usuario->id === 1)
                    <input type="text" value="{{ ucfirst($usuario->estado) }}" 
                           class="w-full px-3 py-2 border rounded bg-gray-700 text-gray-400" disabled>
                @else
                    <select name="estado" class="w-full px-3 py-2 border rounded bg-gray-800 text-white">
                        @foreach(['pendiente','activo','rechazado'] as $e)
                            <option value="{{ $e }}" {{ $usuario->estado === $e ? 'selected' : '' }}>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('usuarios.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Cancelar</a>

                @if($usuario->id !== 1)
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
