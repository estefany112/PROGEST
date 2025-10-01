@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 bg-gray-900 rounded">
    <h1 class="text-2xl font-bold text-white mb-6">Editar Contraseña de Pago</h1>

    @if ($errors->any())
        <div class="bg-red-800 text-white p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contrasenas.update', $contrasena->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

         <!-- Fecha -->
        <div class="mb-4">
            <label class="block text-gray-300 mb-2">Fecha Documento:</label>
            <input type="date" name="fecha_documento"
                value="{{ old('fecha_documento', $contrasena->fecha_documento ?? now()->format('Y-m-d')) }}"
                class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2">
        </div>
        
        <!-- Documento Contraseña -->
        <div class="mb-4">
            <label class="block text-white">Documento Contraseña:</label>
            <input type="text" name="codigo" value="{{ old('codigo', $contrasena->codigo) }}" 
                class="w-full px-3 py-2 rounded bg-gray-800 text-white">
        </div>

        <!-- Estado -->
        <div class="mb-4">
            <label class="block text-white">Estado:</label>
            <select name="estado" class="w-full px-3 py-2 rounded bg-gray-800 text-white">
                <option value="pendiente" {{ $contrasena->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="validada" {{ $contrasena->estado == 'validada' ? 'selected' : '' }}>Validada</option>
            </select>
        </div>

        <!-- Fecha aproximada -->
        <div class="mb-4">
            <label class="block text-white">Fecha aprox:</label>
            <input type="date" name="fecha_aprox" value="{{ old('fecha_aprox', $contrasena->fecha_aprox) }}" 
                class="w-full px-3 py-2 rounded bg-gray-800 text-white">
        </div>

        <!-- Archivo -->
        <div class="mb-4">
            <label class="block text-white">Archivo adjunto:</label>
            @if($contrasena->archivo)
                <p class="mb-2">
                    <a href="{{ asset('storage/'.$contrasena->archivo) }}" target="_blank" class="text-blue-400 hover:underline">
                        Ver archivo actual
                    </a>
                </p>
            @endif
            <input type="file" name="archivo" class="w-full px-3 py-2 rounded bg-gray-800 text-white">
        </div>

        <button type="submit" class="bg-green-600 px-4 py-2 rounded text-white">Actualizar</button>
    </form>

</div>
@endsection
