@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Usuarios Pendientes o Rechazados</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Nombre</th>
                <th class="p-2 border">Correo</th>
                <th class="p-2 border">Estado</th>
                <th class="p-2 border">Rol</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($usuariosPendientes as $user)
            <tr>
                <td class="p-2 border">{{ $user->name }}</td>
                <td class="p-2 border">{{ $user->email }}</td>
                <td class="p-2 border">
                    <span class="px-2 py-1 rounded text-white
                        {{ $user->estado === 'pendiente' ? 'bg-yellow-500' : 'bg-red-500' }}">
                        {{ ucfirst($user->estado) }}
                    </span>
                </td>
                <td class="p-2 border">
                    <form action="{{ route('admin.usuarios.aprobar', $user->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <select name="rol" class="border rounded p-1">
                            <option value="admin">admin</option>
                            <option value="asistente">asistente</option>
                            <option value="otro">otro</option>
                        </select>
                </td>
                <td class="p-2 border flex gap-2">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Aprobar</button>
                    </form>
                    <form action="{{ route('admin.usuarios.rechazar', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Rechazar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-4">No hay usuarios pendientes o rechazados</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
