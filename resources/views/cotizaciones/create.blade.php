

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
                        
                        <!-- Datos del cliente -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="cliente_nombre" class="block text-sm font-medium text-gray-200 mb-2">
                                    Nombre del Cliente *
                                </label>
                                <input type="text" id="cliente_nombre" name="cliente_nombre" 
                                       value="{{ old('cliente_nombre') }}" required
                                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cliente_nombre')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="cliente_nit" class="block text-sm font-medium text-gray-200 mb-2">
                                    NIT *
                                </label>
                                <input type="text" id="cliente_nit" name="cliente_nit" 
                                       value="{{ old('cliente_nit') }}" required
                                       class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cliente_nit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="cliente_direccion" class="block text-sm font-medium text-gray-200 mb-2">
                                Dirección del Cliente *
                            </label>
                            <textarea id="cliente_direccion" name="cliente_direccion" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('cliente_direccion') }}</textarea>
                            @error('cliente_direccion')
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
                        
                        <!-- Items de la cotización -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-100">Items de la Cotización</h3>
                                <button type="button" onclick="agregarItem()" 
                                        class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-plus mr-2"></i>Agregar Item
                                </button>
                            </div>
                            
                            <div id="items-container">
                                <!-- Los items se agregarán dinámicamente aquí -->
                        </div>
                        @error('fecha_emision')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Detalle del Servicio -->
                <div class="bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-100 mb-4">Detalle del Servicio / Términos y Condiciones</h3>
                        <textarea id="detalle_servicio" name="detalle_servicio" rows="10" 
                                  class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('detalle_servicio') }}</textarea>
                        @error('detalle_servicio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Items de la cotización -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Items de la Cotización</h3>
                        
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
                            
                            @if(Route::has('dashboard'))
                                <a href="{{ route('dashboard') }}" 
                                   class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                                    Ir al Dashboard
                                </a>
                            @elseif(Route::has('home'))
                                <a href="{{ route('home') }}" 
                                   class="px-4 py-2 text-gray-300 border border-gray-700 rounded-md hover:bg-gray-700">
                                    Ir al Inicio
                                </a>
                            @endif
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="calcularTotalItem(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Unidad *</label>
                    <input type="text" name="items[INDEX][unidad_medida]" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ej: Unidad, Kg, etc.">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-200 mb-2">Descripción *</label>
                    <input type="text" name="items[INDEX][descripcion]" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Precio Unitario *</label>
                    <input type="number" name="items[INDEX][precio_unitario]" min="0" step="0.01" required
                           class="w-full px-3 py-2 border border-gray-700 bg-gray-900 text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="calcularTotalItem(this)">
                </div>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm font-medium text-gray-200">
                                         Total del Item: <span class="text-blue-400 item-total">Q0.00</span>
                </div>
                <button type="button" onclick="eliminarItem(this)" 
                        class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </template>

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

        // Agregar primer item al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            agregarItem();
        });
    </script>
@endsection
