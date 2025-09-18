@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div>
    <label class="block text-sm text-gray-300 mb-1">Nombre *</label>
    <input name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" required
           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded">
    @error('nombre') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block text-sm text-gray-300 mb-1">NIT *</label>
    <input name="nit" value="{{ old('nit', $cliente->nit ?? '') }}" required
           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded">
    @error('nit') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm text-gray-300 mb-1">Dirección *</label>
    <textarea name="direccion" rows="3" required
              class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded">{{ old('direccion', $cliente->direccion ?? '') }}</textarea>
    @error('direccion') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="flex justify-end gap-3 mt-6">
  <a href="{{ route('clientes.index') }}" class="px-4 py-2 border border-gray-700 text-gray-300 rounded hover:bg-gray-800">Cancelar</a>
  <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
    {{ $submitText ?? 'Guardar' }}
  </button>
</div>
