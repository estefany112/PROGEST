@extends('layouts.app')

@section('content')
<div class="container text-center py-20">
    <h1 class="text-3xl font-bold text-red-600 mb-4">🚫 Acceso Denegado</h1>
    <p class="text-lg text-gray-700 mb-6">{{ $mensaje }}</p>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       class="bg-blue-500 text-white px-4 py-2 rounded">
        Cerrar sesión
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection
