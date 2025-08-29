@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto pt-8 pb-10 px-6">
  <h1 class="text-2xl font-bold text-gray-100 mb-6">Editar Cliente</h1>
  <div class="bg-gray-900 border border-gray-800 rounded-lg p-6">
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
      @method('PUT')
      @include('clientes._form', ['submitText' => 'Actualizar', 'cliente' => $cliente])
    </form>
  </div>
</div>
@endsection
