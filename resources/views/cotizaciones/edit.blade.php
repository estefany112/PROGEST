@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">
            Editar Cotización - {{ $cotizacion->folio }}
        </h1>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">

                {{-- ⚡ Mensajes de error globales --}}
                @if ($errors->any())
                    <div class="bg-red-600 text-white p-3 mb-4 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ⚡ Mensaje de éxito --}}
                @if (session('success'))
                    <div class="bg-green-600 text-white p-3 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST" id="cotizacionForm">
                    @csrf
                    @method('PUT')

                    <!-- Datos del cliente -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-200 mb-2">
                                Empresa (cliente) *
                            </label>
                            <select id="cliente_select" name="cliente_id"
                                class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">-- Selecciona cliente --</option>
                            </select>
                            @error('cliente_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end">
                            <button type="button" id="btnNuevoCliente"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">
                                + Nuevo cliente
                            </button>
                        </div>
                    </div>

                    {{-- Snapshot cliente solo lectura --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Nombre</label>
                            <input id="cliente_nombre_view"
                                class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md"
                                readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">NIT</label>
                            <input id="cliente_nit_view"
                                class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md"
                                readonly>
                        </div>
                        <div class="md:col-span-1 md:col-start-1 md:col-end-4">
                            <label class="block text-sm font-medium text-gray-200 mb-2">Dirección</label>
                            <textarea id="cliente_direccion_view" rows="2"
                                class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md"
                                readonly></textarea>
                        </div>
                    </div>

                    <!-- Fecha emisión -->
                    <div class="mb-6">
                        <label for="fecha_emision" class="block text-sm font-medium text-gray-200 mb-2">
                            Fecha de Emisión *
                        </label>
                        <input type="date" id="fecha_emision" name="fecha_emision"
                            value="{{ old('fecha_emision', $cotizacion->fecha_emision->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('fecha_emision')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Items -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-100">Items de la Cotización</h3>
                            <button type="button" onclick="agregarItem()"
                                class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus mr-2"></i>Agregar Item
                            </button>
                        </div>

                        <div id="items-container">
                            @foreach($cotizacion->items as $index => $item)
                                <div class="item-row border border-gray-700 rounded-lg p-4 mb-4 bg-gray-900">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-200 mb-2">Cantidad *</label>
                                            <input type="number" name="items[{{ $index }}][cantidad]" min="1" required
                                                value="{{ $item->cantidad }}"
                                                class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md"
                                                onchange="calcularTotalItem(this)">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-200 mb-2">Unidad *</label>
                                            <input type="text" name="items[{{ $index }}][unidad_medida]" required
                                                value="{{ $item->unidad_medida }}"
                                                class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-200 mb-2">Descripción *</label>
                                            <input type="text" name="items[{{ $index }}][descripcion]" required
                                                value="{{ $item->descripcion }}"
                                                class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-200 mb-2">Precio Unitario *</label>
                                            <input type="number" name="items[{{ $index }}][precio_unitario]" min="0"
                                                step="0.01" required value="{{ $item->precio_unitario }}"
                                                class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md"
                                                onchange="calcularTotalItem(this)">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center mt-4">
                                        <div class="text-sm font-medium text-gray-200">
                                            Total del Item:
                                            <span class="text-blue-400 item-total">Q{{ number_format($item->total, 2) }}</span>
                                        </div>
                                        <button type="button" onclick="eliminarItem(this)"
                                            class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('items')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Totales -->
                    <div class="bg-gray-800 p-4 rounded-lg mb-6">
                        <div class="mb-2 text-sm text-gray-300 font-semibold">
                            El IVA aplicado es del 12%.
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">Subtotal</label>
                                <div class="text-lg font-semibold text-gray-100" id="subtotal">
                                    Q{{ number_format($cotizacion->subtotal, 2) }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">IVA (12%)</label>
                                <div class="text-lg font-semibold text-gray-100" id="iva">
                                    Q{{ number_format($cotizacion->iva, 2) }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">Total</label>
                                <div class="text-xl font-bold text-blue-400" id="total">
                                    Q{{ number_format($cotizacion->total, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('cotizaciones') }}"
                            class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Actualizar Cotización
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script clientes --}}
<script>
const sel = document.getElementById('cliente_select');
const vNom = document.getElementById('cliente_nombre_view');
const vNit = document.getElementById('cliente_nit_view');
const vDir = document.getElementById('cliente_direccion_view');

async function cargarClientesYSeleccionar() {
    try {
        const res = await fetch('{{ route('clientes.lista-json') }}', { headers: { 'Accept': 'application/json' }});
        const list = await res.json();

        sel.innerHTML = '<option value="">-- Selecciona cliente --</option>';
        list.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.nombre;
            opt.dataset.nit = c.nit || '';
            opt.dataset.dir = c.direccion || '';
            sel.appendChild(opt);
        });

        // Selecciona el cliente actual
        sel.value = '{{ $cotizacion->cliente_id }}';
        const opt = sel.selectedOptions[0];
        if (opt) {
            vNom.value = opt.textContent.trim();
            vNit.value = opt.dataset.nit || '';
            vDir.value = opt.dataset.dir || '';
        }
    } catch (e) {
        console.error('No se pudo cargar clientes', e);
    }
}

sel.addEventListener('change', () => {
    const opt = sel.selectedOptions[0];
    vNom.value = opt ? opt.textContent.trim() : '';
    vNit.value = opt ? (opt.dataset.nit || '') : '';
    vDir.value = opt ? (opt.dataset.dir || '') : '';
});

document.addEventListener('DOMContentLoaded', cargarClientesYSeleccionar);
</script>
@endsection
