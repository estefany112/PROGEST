@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">

  @if(session('success'))
    <div class="mb-4 bg-green-900/40 border border-green-700 text-green-300 px-4 py-2 rounded">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-4 bg-red-900/40 border border-red-700 text-red-300 px-4 py-2 rounded">
      {{ session('error') }}
    </div>
  @endif

  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-100">Clientes</h1>
    <a href="{{ route('clientes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
      + Nuevo Cliente
    </a>
  </div>

  <form method="GET" class="mb-6">
    <div class="flex gap-3">
      <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por nombre, NIT o dirección..."
             class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded">
      <button class="px-4 py-2 border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Buscar</button>
      @if($q)
        <a href="{{ route('clientes.index') }}" class="px-4 py-2 border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Limpiar</a>
      @endif
    </div>
  </form>

  <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-800">
      <thead class="bg-gray-800">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nombre</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">NIT</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Dirección</th>
          <th class="px-4 py-3 text-right text-xs font-medium text-gray-300 uppercase">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-800">
        @forelse ($clientes as $c)
          <tr>
            <td class="px-4 py-3 text-gray-100">{{ $c->nombre }}</td>
            <td class="px-4 py-3 text-gray-100">{{ $c->nit }}</td>
            <td class="px-4 py-3 text-gray-100">{{ $c->direccion }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                <a href="{{ route('clientes.show', $c) }}" class="px-3 py-1 text-sm border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Ver</a>
                <a href="{{ route('clientes.edit', $c) }}" class="px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded">Editar</a>
                <form action="{{ route('clientes.destroy', $c) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente?');">
                  @csrf @method('DELETE')
                  <button class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="px-4 py-6 text-center text-gray-400" colspan="4">No hay clientes.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $clientes->links() }}
  </div>
</div>
@endsection
