<div class="overflow-x-auto">
    <table class="min-w-full bg-gray-900 rounded-lg">
        <thead class="bg-gray-800">
            <tr>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Folio</th>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Cliente</th>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Fecha</th>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Total</th>
                <th class="px-6 py-3 text-center text-sm text-gray-300">Acciones</th>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Creada por</th>
                <th class="px-6 py-3 text-left text-sm text-gray-300">Estado</th>
            </tr>
        </thead>
        <tbody class="bg-gray-900 divide-y divide-gray-800">
            @foreach($cotizaciones as $cotizacion)
                <tr>
                    <td class="px-6 py-4 text-white">{{ $cotizacion->folio }}</td>
                    <td class="px-6 py-4 text-white">{{ $cotizacion->cliente_nombre }}</td>
                    <td class="px-6 py-4 text-white">{{ $cotizacion->fecha_emision->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-white">Q{{ number_format($cotizacion->total, 2) }}</td>

                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('cotizaciones.cambiar-estado', $cotizacion) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="estado" class="bg-gray-800 text-white px-2 py-1 rounded">
                                <option value="aprobada" {{ $cotizacion->estado == 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="rechazada" {{ $cotizacion->estado == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-1 rounded ml-2">Actualizar</button>
                        </form>
                    </td>

                    <td class="px-6 py-4 text-white">{{ $cotizacion->creadaPor->name }}</td>
                    <td class="px-6 py-4 text-white">{{ ucfirst($cotizacion->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
