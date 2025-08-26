@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold text-white mb-6">Debug Rol de Usuario</h1>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow text-white">
        <p><strong>Usuario autenticado:</strong> {{ Auth::user()->name }} (ID: {{ Auth::id() }})</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Estado:</strong> {{ Auth::user()->estado }}</p>
        <p><strong>Roles asignados:</strong> {{ Auth::user()->getRoleNames()->implode(', ') }}</p>
        <p><strong>¿Es admin?</strong> {{ Auth::user()->hasRole('admin') ? 'Sí' : 'No' }}</p>
        <p><strong>¿Es asistente?</strong> {{ Auth::user()->hasRole('asistente') ? 'Sí' : 'No' }}</p>
    </div>
</div>
@endsection
