@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto pt-8 pb-10 px-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-100">Detalle del Cliente</h1>
    <a href="{{ route('clientes.index') }}" class="px-4 py-2 border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Volver</a>
  </div>

  <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 space-y-3">
    <div><span class="text-gray-400">Nombre:</span> <span class="text-gray-100">{{ $cliente->nombre }}</span></div>
    <div><span class="text-gray-400">NIT:</span> <span class="text-gray-100">{{ $cliente->nit }}</span></div>
    <div><span class="text-gray-400">Dirección:</span> <span class="text-gray-100">{{ $cliente->direccion }}</span></div>
  </div>
</div>
@endsection
