<?php

namespace Database\Seeders;

use App\Models\Cotizacion;
use App\Models\ItemCotizacion;
use App\Models\User;
use Illuminate\Database\Seeder;

class CotizacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios existentes
        $admin = User::where('tipo', 'admin')->first();
        $asistente = User::where('tipo', 'asistente')->first();

        if (!$admin || !$asistente) {
            $this->command->error('No se encontraron usuarios admin y asistente. Ejecute RolesYUsuariosSeeder primero.');
            return;
        }

        // Crear cotizaciones de ejemplo
        $cotizaciones = [
            [
                'cliente_nombre' => 'Empresa ABC S.A.',
                'cliente_direccion' => 'Calle 123 #45-67, Bogotá, Colombia',
                'cliente_nit' => '900123456-7',
                'fecha_emision' => now()->subDays(5),
                'estado' => 'aprobada',
                'creada_por' => $asistente->id,
                'revisada_por' => $admin->id,
                'items' => [
                    [
                        'cantidad' => 10,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Laptop Dell Inspiron 15',
                        'precio_unitario' => 2500000,
                    ],
                    [
                        'cantidad' => 5,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Monitor Samsung 24"',
                        'precio_unitario' => 800000,
                    ],
                ]
            ],
            [
                'cliente_nombre' => 'Compañía XYZ Ltda.',
                'cliente_direccion' => 'Avenida Principal #89-12, Medellín, Colombia',
                'cliente_nit' => '800987654-3',
                'fecha_emision' => now()->subDays(3),
                'estado' => 'en_revision',
                'creada_por' => $asistente->id,
                'items' => [
                    [
                        'cantidad' => 20,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Teclado mecánico RGB',
                        'precio_unitario' => 150000,
                    ],
                    [
                        'cantidad' => 20,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Mouse gaming inalámbrico',
                        'precio_unitario' => 120000,
                    ],
                    [
                        'cantidad' => 10,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Auriculares con micrófono',
                        'precio_unitario' => 200000,
                    ],
                ]
            ],
            [
                'cliente_nombre' => 'Corporación DEF',
                'cliente_direccion' => 'Carrera 50 #30-15, Cali, Colombia',
                'cliente_nit' => '700456789-1',
                'fecha_emision' => now()->subDays(1),
                'estado' => 'borrador',
                'creada_por' => $asistente->id,
                'items' => [
                    [
                        'cantidad' => 2,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Servidor HP ProLiant DL380',
                        'precio_unitario' => 15000000,
                    ],
                    [
                        'cantidad' => 4,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Disco duro SSD 1TB',
                        'precio_unitario' => 300000,
                    ],
                ]
            ],
            [
                'cliente_nombre' => 'Empresa GHI',
                'cliente_direccion' => 'Calle 15 #22-33, Barranquilla, Colombia',
                'cliente_nit' => '600789123-4',
                'fecha_emision' => now()->subDays(7),
                'estado' => 'rechazada',
                'creada_por' => $asistente->id,
                'revisada_por' => $admin->id,
                'comentario_rechazo' => 'Precios muy elevados para el presupuesto disponible. Se requiere revisión de costos.',
                'items' => [
                    [
                        'cantidad' => 15,
                        'unidad_medida' => 'Unidad',
                        'descripcion' => 'Impresora láser HP LaserJet Pro',
                        'precio_unitario' => 1200000,
                    ],
                ]
            ],
        ];

        foreach ($cotizaciones as $cotizacionData) {
            $items = $cotizacionData['items'];
            unset($cotizacionData['items']);

            // Crear cotización
            $cotizacion = Cotizacion::create([
                'folio' => Cotizacion::generarFolio(),
                'fecha_emision' => $cotizacionData['fecha_emision'],
                'cliente_nombre' => $cotizacionData['cliente_nombre'],
                'cliente_direccion' => $cotizacionData['cliente_direccion'],
                'cliente_nit' => $cotizacionData['cliente_nit'],
                'subtotal' => 0,
                'iva' => 0,
                'total' => 0,
                'estado' => $cotizacionData['estado'],
                'comentario_rechazo' => $cotizacionData['comentario_rechazo'] ?? null,
                'creada_por' => $cotizacionData['creada_por'],
                'revisada_por' => $cotizacionData['revisada_por'] ?? null,
            ]);

            // Crear items
            foreach ($items as $itemData) {
                ItemCotizacion::create([
                    'cotizacion_id' => $cotizacion->id,
                    'cantidad' => $itemData['cantidad'],
                    'unidad_medida' => $itemData['unidad_medida'],
                    'descripcion' => $itemData['descripcion'],
                    'precio_unitario' => $itemData['precio_unitario'],
                ]);
            }

            // Calcular totales
            $cotizacion->calcularTotales();
        }

        $this->command->info('Cotizaciones de ejemplo creadas exitosamente.');
    }
} 