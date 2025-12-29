@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-6">
  <div class="flex items-center justify-between mb-6">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
  <script>
  Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: @json(session('success')),
    timer: 1800,
    showConfirmButton: false,
    background: '#111827',
    color: '#e5e7eb'
  });
</script>
@endif

@if(session('error'))
  <script>
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: @json(session('error')),
    background: '#111827',
    color: '#e5e7eb'
  });
  </script>
@endif

    <h1 class="text-2xl font-bold text-white">Usuarios</h1>
  </div>

  {{-- Filtros / búsqueda --}}
  <form method="GET" class="flex gap-3 mb-4">
    <input name="q" value="{{ request('q') }}" 
           placeholder="Buscar nombre, email o estado..." 
           class="px-3 py-2 border rounded bg-gray-800 text-white">

    <select name="estado" class="px-3 py-2 border rounded bg-gray-800 text-white">
        <option value="">-- Estado --</option>
        @foreach(['pendiente', 'activo', 'rechazado'] as $e)
            <option value="{{ $e }}" @selected(request('estado') === $e)>
                {{ ucfirst($e) }}
            </option>
        @endforeach
    </select>

    <select name="rol" class="px-3 py-2 border rounded bg-gray-800 text-white">
        <option value="">-- Rol --</option>
        @foreach($roles as $r)
            <option value="{{ $r }}" @selected(request('rol') === $r)>
                {{ ucfirst($r) }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="bg-blue-600 px-4 py-2 text-white rounded">Filtrar</button>
  </form>

  <div class="bg-gray-900 border border-gray-700 rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-700">
      <thead class="bg-gray-800 text-gray-300 text-xs uppercase">
        <tr>
          <th class="px-4 py-3 text-left">Nombre</th>
          <th class="px-4 py-3 text-left">Email</th>
          <th class="px-4 py-3 text-left">Rol</th>
          <th class="px-4 py-3 text-left">Estado</th>
          <th class="px-4 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="bg-gray-800 divide-y divide-gray-700 text-gray-100">
        @forelse($usuarios as $u)
          @php
            $badge = ['activo'=>'bg-green-700','pendiente'=>'bg-yellow-700','rechazado'=>'bg-red-700'][$u->estado] ?? 'bg-gray-700';
          @endphp
          <tr>
            <td class="px-4 py-3">{{ $u->name }}</td>
            <td class="px-4 py-3">{{ $u->email }}</td>
            <td class="px-4 py-3">
              @if($u->id === 1)
                  <span class="text-gray-400 bold italic">Admin (Protegido)</span>
              @else
                @php
                    $rol = $u->getRoleNames()->first();

                    $color = match ($rol) {
                        'admin' => 'bg-green-700 text-white',
                        'asistente' => 'bg-purple-900 text-white',
                        default => 'bg-gray-300 text-gray-700',
                    };
                @endphp

                <span class="px-2 py-1 text-xs rounded {{ $color }}">
                    {{ ucfirst($rol ?? 'Sin rol') }}
                </span>
              @endif
            </td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 text-xs rounded {{ $badge }}">{{ ucfirst($u->estado) }}</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-end gap-2">
                @if($u->id !== 1) 
                  <a href="{{ route('usuarios.edit',$u->id) }}" class="px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded">Editar</a>
                  <form id="form-delete-{{ $u->id }}" 
                      action="{{ route('usuarios.destroy', $u->id) }}" 
                      method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="button"
                          onclick="confirmarEliminacion({{ $u->id }}, '{{ $u->name }}')"
                          class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded">
                    Eliminar
                  </button>
                </form>
                
                @else
                  <span class="text-gray-400 italic">Protegido</span>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No hay usuarios.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $usuarios->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmarEliminacion(id, nombre) {
    Swal.fire({
      title: '¿Eliminar cliente?',
      html: `<p>El cliente <strong>${nombre}</strong> será eliminado permanentemente.</p>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e3342f',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
      background: '#111827',
      color: '#e5e7eb',
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(`form-delete-${id}`).submit();
      }
    });
  }
</script>

@endsection
