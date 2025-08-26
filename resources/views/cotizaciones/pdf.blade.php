<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización {{ $cotizacion->folio }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 12px;
            color: #666;
        }
        .document-title {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        .info-section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .client-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 12px;
        }
        .items-table .text-right {
            text-align: right;
        }
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-table {
            width: 300px;
        }
        .totals-table td {
            padding: 8px;
            font-size: 14px;
        }
        .totals-table .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-borrador { background-color: #6c757d; color: white; }
        .status-en_revision { background-color: #ffc107; color: black; }
        .status-aprobada { background-color: #28a745; color: white; }
        .status-rechazada { background-color: #dc3545; color: white; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">EMPRESA PROGEST</div>
        <div class="company-info">
            Sistema de Gestión de Proyectos<br>
            Tel: (123) 456-7890 | Email: info@progest.com<br>
            Dirección: Calle Principal #123, Ciudad
        </div>

    @if(!empty($cotizacion->detalle_servicio))
    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
        <h3 style="margin: 0 0 10px 0; color: #333;">Detalle del Servicio / Términos y Condiciones</h3>
        <div style="white-space: pre-line; color: #222; font-size: 14px;">{{ $cotizacion->detalle_servicio }}</div>
    </div>
    @endif
    </div>

    <div class="document-title">COTIZACIÓN</div>

    <div class="document-info">
        <div class="info-section">
            <h3>Información del Documento</h3>
            <p><strong>Folio:</strong> {{ $cotizacion->folio }}</p>
            <p><strong>Fecha de Emisión:</strong> {{ $cotizacion->fecha_emision->format('d/m/Y') }}</p>
            <p><strong>Estado:</strong> 
                <span class="status-badge status-{{ $cotizacion->estado }}">
                    {{ $cotizacion->estado_texto }}
                </span>
            </p>
        </div>
        <div class="info-section">
            <h3>Información del Sistema</h3>
            <p><strong>Creada por:</strong> {{ $cotizacion->creadaPor->name }}</p>
            @if($cotizacion->revisadaPor)
                <p><strong>Revisada por:</strong> {{ $cotizacion->revisadaPor->name }}</p>
            @endif
            <p><strong>Fecha de Creación:</strong> {{ $cotizacion->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="client-info">
        <h3>Información del Cliente</h3>
        <p><strong>Nombre:</strong> {{ $cotizacion->cliente_nombre }}</p>
        <p><strong>NIT:</strong> {{ $cotizacion->cliente_nit }}</p>
        <p><strong>Dirección:</strong> {{ $cotizacion->cliente_direccion }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">Cantidad</th>
                <th style="width: 15%;">Unidad</th>
                <th style="width: 45%;">Descripción</th>
                <th style="width: 15%;">Precio Unitario</th>
                <th style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cotizacion->items as $item)
                <tr>
                    <td>{{ $item->cantidad }}</td>
                    <td>{{ $item->unidad_medida }}</td>
                    <td>{{ $item->descripcion }}</td>
                    <td class="text-right">Q{{ number_format($item->precio_unitario, 2) }}</td>
                    <td class="text-right">Q{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td class="text-right">Q{{ number_format($cotizacion->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>IVA (19%):</strong></td>
                <td class="text-right">Q{{ number_format($cotizacion->iva, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right">Q{{ number_format($cotizacion->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($cotizacion->comentario_rechazo)
        <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
            <h3 style="color: #721c24; margin: 0 0 10px 0;">Comentario de Rechazo</h3>
            <p style="color: #721c24; margin: 0;">{{ $cotizacion->comentario_rechazo }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Este documento fue generado automáticamente por el sistema PROGEST</p>
        <p>Fecha de impresión: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
    <!-- Botones de imprimir y cerrar eliminados para descarga directa -->
</body>
</html> 