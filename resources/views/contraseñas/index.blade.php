@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Contraseñas de Pago</h1>

    <form method="POST" action="{{ route('contraseñas.store') }}" class="mb-6">
        @csrf
        <div class="flex gap-4">
            <select name="factura_id" class="border px-3 py-2 rounded bg-gray-800 text-white">
                @foreach($facturas as $factura)
                <option value="{{ $factura->id }}">Factura #{{ $factura->numero_factura }}</option>
                @endforeach
            </select>

            <input type="text" name="contraseña" placeholder="Contraseña de pago" class="border px-3 py-2 rounded bg-gray-800 text-white" required>

            <button type="submit" class="bg-purple-600 hover:bg-purple-800 text-white px-4 py-2 rounded">
                Guardar
            </button>
        </div>
    </form>

    <table class="w-full bg-gray-900 rounded shadow">
        <thead class="bg-gray-800 text-gray-300 uppercase text-sm">
            <tr>
                <th class="px-4 py-2">Factura</th>
                <th class="px-4 py-2">Contraseña</th>
                <th class="px-4 py-2">Validada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contraseñas as $c)
            <tr class="border-t border-gray-700">
                <td class="px-4 py-2 text-white">#{{ $c->factura->numero_factura }}</td>
                <td class="px-4 py-2 text-white">{{ $c->contraseña }}</td>
                <td class="px-4 py-2 text-white">{{ $c->validada ? 'Sí' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
