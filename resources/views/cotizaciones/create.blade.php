

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-10 px-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-100">Nueva Cotización</h1>
        <div class="flex space-x-2">
            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Dashboard</a>
            @elseif(Route::has('home'))
                <a href="{{ route('home') }}" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Inicio</a>
            @endif
            @if(Route::has('profile.show'))
                <a href="{{ route('profile.show') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ver mi perfil</a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                    <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
                        @csrf
                        
                       @csrf
                    
                    <!-- Datos del cliente -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-200 mb-2">Empresa (cliente) *</label>
                            <select id="cliente_select" name="cliente_id"
                                    class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">-- Selecciona cliente --</option>
                            </select>
                            @error('cliente_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="fecha_emision" class="block text-sm font-medium text-gray-200 mb-2">
                                Fecha de Emisión *
                            </label>
                            <input type="date" id="fecha_emision" name="fecha_emision" 
                                   value="{{ old('fecha_emision', date('Y-m-d')) }}" required
                                   class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('fecha_emision')
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

                    {{-- Snapshot solo lectura --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Nombre</label>
                            <input id="cliente_nombre_view" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">NIT</label>
                            <input id="cliente_nit_view" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" readonly>
                        </div>
                        <div class="md:col-span-1 md:col-start-1 md:col-end-4">
                            <label class="block text-sm font-medium text-gray-200 mb-2">Dirección</label>
                            <textarea id="cliente_direccion_view" rows="2" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" readonly></textarea>
                        </div>
                    </div>

                    <!-- Items de la cotización -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-100">Items de la Cotización</h3>
                            <button type="button" onclick="agregarItem()" 
                                    class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus mr-2"></i>Agregar Item
                            </button>
                        </div>
                        <div id="items-container"></div>
                    </div>

                    <!-- Detalle del Servicio -->
                    <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-100 mb-4">Detalle del Servicio / Términos y Condiciones</h3>
                            <textarea id="detalle_servicio" name="detalle_servicio" rows="10" 
                                      class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('detalle_servicio') }}</textarea>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="bg-gray-800 p-4 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">Subtotal</label>
                                <div class="text-lg font-semibold text-gray-100" id="subtotal">Q0.00</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">IVA (12%)</label>
                                <div class="text-lg font-semibold text-gray-100" id="iva">Q0.00</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-200 mb-2">Total</label>
                                <div class="text-xl font-bold text-blue-400" id="total">Q0.00</div>
                            </div>
                        </div>
                    </div>

                   <!-- Botones -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('cotizaciones.index') }}"
                        class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                            Volver al listado
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Crear Cotización
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template para items -->
<template id="item-template">
    <div class="item-row border border-gray-700 rounded-lg p-4 mb-4 bg-gray-900">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">Cantidad *</label>
                <input type="number" name="items[INDEX][cantidad]" min="1" required
                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md"
                       onchange="calcularTotalItem(this)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">Unidad *</label>
                <input type="text" name="items[INDEX][unidad_medida]" required
                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md"
                       placeholder="ej: Unidad, Kg, etc.">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-200 mb-2">Descripción *</label>
                <input type="text" name="items[INDEX][descripcion]" required
                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">Precio Unitario *</label>
                <input type="number" name="items[INDEX][precio_unitario]" min="0" step="0.01" required
                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md"
                       onchange="calcularTotalItem(this)">
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <div class="text-sm font-medium text-gray-200">
                Total del Item: <span class="text-blue-400 item-total">Q0.00</span>
            </div>
            <button type="button" onclick="eliminarItem(this)" class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
    </div>
</template>

<!-- MODAL: Nuevo Cliente (fuera del template) -->
<div id="modalCliente" class="fixed inset-0 bg-black/50 hidden items-center justify-center p-4 z-50">
    <div class="bg-gray-900 border border-gray-700 rounded-lg w-full max-w-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-100">Nuevo cliente</h2>
            <button id="modalClose" class="text-gray-400 hover:text-gray-200">&times;</button>
        </div>

        <div id="modalError" class="hidden bg-red-900/40 text-red-300 border border-red-700 p-2 rounded mb-3 text-sm"></div>

        <form id="formNuevoCliente" class="space-y-3">
            @csrf
            <label class="block">
                <span class="block text-sm text-gray-300 mb-1">Nombre *</span>
                <input name="nombre" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" required>
            </label>
            <label class="block">
                <span class="block text-sm text-gray-300 mb-1">NIT *</span>
                <input name="nit" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" required>
            </label>
            <label class="block">
                <span class="block text-sm text-gray-300 mb-1">Dirección *</span>
                <textarea name="direccion" rows="3" class="w-full px-3 py-2 border border-gray-700 bg-gray-800 text-gray-100 rounded-md" required></textarea>
            </label>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" id="btnCancelar" class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-800">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    let itemIndex = 0;

    function agregarItem() {
        const container = document.getElementById('items-container');
        const template = document.getElementById('item-template');
        const itemHtml = template.innerHTML.replace(/INDEX/g, itemIndex);
        
        const itemDiv = document.createElement('div');
        itemDiv.innerHTML = itemHtml;
        container.appendChild(itemDiv);
        
        itemIndex++;
    }

    function eliminarItem(button) {
        button.closest('.item-row').remove();
        calcularTotales();
    }

    function calcularTotalItem(input) {
        const row = input.closest('.item-row');
        const cantidad = row.querySelector('input[name*="[cantidad]"]').value || 0;
        const precio = row.querySelector('input[name*="[precio_unitario]"]').value || 0;
        const total = cantidad * precio;
        
        row.querySelector('.item-total').textContent = `Q${total.toFixed(2)}`;
        calcularTotales();
    }

    function calcularTotales() {
        let subtotal = 0;
        const items = document.querySelectorAll('.item-row');
        
        items.forEach(item => {
            const cantidad = item.querySelector('input[name*="[cantidad]"]').value || 0;
            const precio = item.querySelector('input[name*="[precio_unitario]"]').value || 0;
            subtotal += cantidad * precio;
        });
        
        const iva = subtotal * 0.12;
        const total = subtotal + iva;
        
        document.getElementById('subtotal').textContent = `Q${subtotal.toFixed(2)}`;
        document.getElementById('iva').textContent = `Q${iva.toFixed(2)}`;
        document.getElementById('total').textContent = `Q${total.toFixed(2)}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        agregarItem();
    });

    // CLIENTES
    const sel = document.querySelector('#cliente_select');
    const vNom = document.querySelector('#cliente_nombre_view');
    const vNit = document.querySelector('#cliente_nit_view');
    const vDir = document.querySelector('#cliente_direccion_view');

    async function cargarClientes() {
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
        } catch (e) {
            console.error('No se pudo cargar la lista de clientes', e);
        }
    }
    cargarClientes();

    sel.addEventListener('change', () => {
        const opt = sel.selectedOptions[0];
        if (!opt || !opt.value) {
            vNom.value = ''; vNit.value = ''; vDir.value = '';
            return;
        }
        vNom.value = opt.textContent.trim();
        vNit.value = opt.dataset.nit || '';
        vDir.value = opt.dataset.dir || '';
    });

    // Modal
    const modal = document.querySelector('#modalCliente');
    const openBtn = document.querySelector('#btnNuevoCliente');
    const closeBtn = document.querySelector('#modalClose');
    const cancelBtn = document.querySelector('#btnCancelar');
    const form = document.querySelector('#formNuevoCliente');
    const errorBox = document.querySelector('#modalError');

    const abrir = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); errorBox.classList.add('hidden'); };
    const cerrar = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); form.reset(); errorBox.classList.add('hidden'); };

    openBtn.addEventListener('click', abrir);
    closeBtn.addEventListener('click', cerrar);
    cancelBtn.addEventListener('click', cerrar);
    modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorBox.classList.add('hidden');

        const fd = new FormData(form);
        try {
            const res = await fetch('{{ route('clientes.guardar') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            });
            const data = await res.json();

            if (!res.ok || !data.ok) {
                errorBox.textContent = data.message || 'Error al crear cliente.';
                errorBox.classList.remove('hidden');
                return;
            }

            const c = data.cliente;

            const opt = document.createElement('option');
            opt.value = c.id; opt.textContent = c.nombre;
            opt.dataset.nit = c.nit || '';
            opt.dataset.dir = c.direccion || '';
            sel.appendChild(opt);

            sel.value = c.id;
            vNom.value = c.nombre || '';
            vNit.value = c.nit || '';
            vDir.value = c.direccion || '';

            cerrar();
        } catch (err) {
            errorBox.textContent = 'No se pudo comunicar con el servidor.';
            errorBox.classList.remove('hidden');
        }
    });
</script>
@endsection
