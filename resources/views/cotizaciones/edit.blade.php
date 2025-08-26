<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cotización') }} - {{ $cotizacion->folio }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('cotizaciones.update', $cotizacion) }}" method="POST" id="cotizacionForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Datos del cliente -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="cliente_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Cliente *
                                </label>
                                <input type="text" id="cliente_nombre" name="cliente_nombre" 
                                       value="{{ old('cliente_nombre', $cotizacion->cliente_nombre) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cliente_nombre')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="cliente_nit" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIT *
                                </label>
                                <input type="text" id="cliente_nit" name="cliente_nit" 
                                       value="{{ old('cliente_nit', $cotizacion->cliente_nit) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('cliente_nit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="cliente_direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección del Cliente *
                            </label>
                            <textarea id="cliente_direccion" name="cliente_direccion" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('cliente_direccion', $cotizacion->cliente_direccion) }}</textarea>
                            @error('cliente_direccion')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="fecha_emision" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Emisión *
                            </label>
                            <input type="date" id="fecha_emision" name="fecha_emision" 
                                   value="{{ old('fecha_emision', $cotizacion->fecha_emision->format('Y-m-d')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('fecha_emision')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Items de la cotización -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Items de la Cotización</h3>
                                <button type="button" onclick="agregarItem()" 
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-plus mr-2"></i>Agregar Item
                                </button>
                            </div>
                            
                            <div id="items-container">
                                @foreach($cotizacion->items as $index => $item)
                                    <div class="item-row border border-gray-200 rounded-lg p-4 mb-4 bg-white">
                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad *</label>
                                                <input type="number" name="items[{{ $index }}][cantidad]" min="1" required
                                                       value="{{ $item->cantidad }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                       onchange="calcularTotalItem(this)">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unidad *</label>
                                                <input type="text" name="items[{{ $index }}][unidad_medida]" required
                                                       value="{{ $item->unidad_medida }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                       placeholder="ej: Unidad, Kg, etc.">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                                                <input type="text" name="items[{{ $index }}][descripcion]" required
                                                       value="{{ $item->descripcion }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Unitario *</label>
                                                <input type="number" name="items[{{ $index }}][precio_unitario]" min="0" step="0.01" required
                                                       value="{{ $item->precio_unitario }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                       onchange="calcularTotalItem(this)">
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center mt-4">
                                            <div class="text-sm font-medium text-gray-700">
                                                Total del Item: <span class="text-blue-600 item-total">${{ number_format($item->total, 2) }}</span>
                                            </div>
                                            <button type="button" onclick="eliminarItem(this)" 
                                                    class="text-red-600 hover:text-red-900">
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
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <div class="mb-2 text-sm text-gray-600 font-semibold">
                                <span>El IVA aplicado es del 12%.</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                                    <div class="text-lg font-semibold text-gray-900" id="subtotal">Q{{ number_format($cotizacion->subtotal, 2) }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IVA (12%)</label>
                                    <div class="text-lg font-semibold text-gray-900" id="iva">Q{{ number_format($cotizacion->iva, 2) }}</div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                                    <div class="text-xl font-bold text-blue-600" id="total">Q{{ number_format($cotizacion->total, 2) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('cotizaciones.show', $cotizacion) }}" 
                               class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
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

    <!-- Template para items -->
    <template id="item-template">
        <div class="item-row border border-gray-200 rounded-lg p-4 mb-4 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad *</label>
                    <input type="number" name="items[INDEX][cantidad]" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="calcularTotalItem(this)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unidad *</label>
                    <input type="text" name="items[INDEX][unidad_medida]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="ej: Unidad, Kg, etc.">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                    <input type="text" name="items[INDEX][descripcion]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio Unitario *</label>
                    <input type="number" name="items[INDEX][precio_unitario]" min="0" step="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           onchange="calcularTotalItem(this)">
                </div>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm font-medium text-gray-700">
                    Total del Item: <span class="text-blue-600 item-total">Q0.00</span>
                </div>
                <button type="button" onclick="eliminarItem(this)" 
                        class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </template>

    <script>
        let itemIndex = {{ count($cotizacion->items) }};

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

        // Calcular totales iniciales
        document.addEventListener('DOMContentLoaded', function() {
            calcularTotales();
        });
    </script>
</x-app-layout>
