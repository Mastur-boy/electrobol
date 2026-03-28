{{-- resources/views/reparaciones/factura.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - Reparación #{{ $reparacione->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .factura-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #77dd77;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #77dd77;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .factura-title {
            text-align: center;
            margin: 20px 0;
        }
        .factura-title h2 {
            color: #555;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .info-box {
            flex: 1;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0fdf4;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #ddd;
        }
        .totals p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .payment-methods {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0fdf4;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="factura-container">
        <div class="header">
            <h1>ElectroBol</h1>
            <p>Taller de Reparación de Electrodomésticos</p>
            <p>NIT: 123456789 | Tel: 76543210 | La Paz - Bolivia</p>
        </div>

        <div class="factura-title">
            <h2>FACTURA DE SERVICIO</h2>
            <p><strong>N° Factura:</strong> F-{{ str_pad($reparacione->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="info-row">
            <div class="info-box">
                <p><span class="info-label">Cliente:</span> {{ $reparacione->articulo->cliente->nombre_completo }}</p>
                <p><span class="info-label">NIT/CI:</span> {{ $reparacione->articulo->cliente->ci ?? 'No registrado' }}</p>
            </div>
            <div class="info-box">
                <p><span class="info-label">Fecha:</span> {{ now()->format('d/m/Y H:i') }}</p>
                <p><span class="info-label">Teléfono:</span> {{ $reparacione->articulo->cliente->telefono ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="info-row">
            <div class="info-box">
                <p><span class="info-label">Artículo:</span> {{ $reparacione->articulo->tipo }} {{ $reparacione->articulo->marca }} {{ $reparacione->articulo->modelo }}</p>
                <p><span class="info-label">Serial:</span> {{ $reparacione->articulo->serial ?? 'N/A' }}</p>
            </div>
            <div class="info-box">
                <p><span class="info-label">Técnico:</span> {{ $reparacione->tecnico->nombre_completo }}</p>
                <p><span class="info-label">Estado:</span> {{ ucfirst($reparacione->estado) }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Servicio de Reparación - {{ $reparacione->articulo->tipo }} {{ $reparacione->articulo->marca }}</td>
                    <td>Bs {{ number_format($reparacione->costo_reparacion ?? 0, 2) }}</td>
                    <td>Bs {{ number_format($reparacione->costo_reparacion ?? 0, 2) }}</td>
                </tr>
                @foreach($reparacione->repuestos as $repuesto)
                <tr>
                    <td>{{ $repuesto->pivot->cantidad }}</td>
                    <td>{{ $repuesto->nombre }} ({{ $repuesto->codigo }})</td>
                    <td>Bs {{ number_format($repuesto->pivot->precio_unitario, 2) }}</td>
                    <td>Bs {{ number_format($repuesto->pivot->precio_unitario * $repuesto->pivot->cantidad, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Subtotal:</strong> Bs {{ number_format(($reparacione->costo_reparacion ?? 0) + $reparacione->repuestos->sum(function($r) { return $r->pivot->precio_unitario * $r->pivot->cantidad; }), 2) }}</p>
            <p><strong>IVA (13%):</strong> Bs {{ number_format((($reparacione->costo_reparacion ?? 0) + $reparacione->repuestos->sum(function($r) { return $r->pivot->precio_unitario * $r->pivot->cantidad; })) * 0.13, 2) }}</p>
            <p><strong style="font-size: 18px;">TOTAL:</strong> <strong style="font-size: 18px; color: #77dd77;">Bs {{ number_format((($reparacione->costo_reparacion ?? 0) + $reparacione->repuestos->sum(function($r) { return $r->pivot->precio_unitario * $r->pivot->cantidad; })) * 1.13, 2) }}</strong></p>
        </div>

        <div class="payment-methods">
            <p><strong>Formas de Pago:</strong> Efectivo | Tarjeta | Transferencia Bancaria</p>
            <p>Gracias por confiar en ElectroBol</p>
        </div>

        <div class="footer">
            <p>Este documento es un comprobante válido de pago.</p>
            <p>Para reclamos o consultas: electrobol@taller.com | 76543210</p>
        </div>
    </div>
</body>
</html>