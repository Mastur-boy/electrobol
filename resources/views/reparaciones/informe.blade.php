{{-- resources/views/reparaciones/informe.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Técnico - Reparación #{{ $reparacione->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }
        .informe-container {
            max-width: 990px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .informe-header {
            background: linear-gradient(135deg, #77dd77 0%, #66cc66 100%);
            color: white;
            padding: 35px; 
            text-align: center;
        }
        .informe-header h2 {
            font-size: 35px;
            margin-bottom: 5px;
        }
        .informe-header p {
            opacity: 0.9;
            font-size: 22px;
        }
        .informe-body {
            padding: 30px;
        }
        .informe-title {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #77dd77;
        }
        .informe-title h2 {
            color: #333;
            font-size: 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #f0fdf4;
            padding: 10px 15px;
            font-weight: bold;
            color: #77dd77;
            border-left: 4px solid #77dd77;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            padding: 0 15px;
        }
        .info-item {
            margin-bottom: 12px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
        }
        .info-value {
            color: #333;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        .table th {
            background-color: #f0fdf4;
            font-weight: bold;
        }
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding-top: 30px;
        }
        .signature-line {
            text-align: center;
            width: 200px;
        }
        .signature-line hr {
            margin: 30px 0 10px;
            border: none;
            border-top: 1px solid #ddd;
        }
        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .informe-container {
                box-shadow: none;
                margin: 0;
            }
            .btn-print {
                display: none;
            }
        }
        .btn-print {
            display: inline-block;
            background: #77dd77;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-print:hover {
            background: #66cc66;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn-print">
            🖨️ Imprimir Informe
        </button>
    </div>

    <div class="informe-container">
        <div class="informe-header">
            <h1>ElectroBol</h1>
            <p>Taller de Reparación de Electrodomésticos</p>
            <p>Informe Técnico de Reparación</p>
        </div>

        <div class="informe-body">
            <div class="informe-title">
                <h2>Informe Técnico N° {{ $reparacione->id }}</h2>
                <p>Fecha: {{ now()->format('d/m/Y') }}</p>
            </div>

            {{-- DATOS DEL CLIENTE --}}
            <div class="section">
                <div class="section-title">📋 DATOS DEL CLIENTE</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <div class="info-value">{{ $reparacione->articulo->cliente->nombre }} {{ $reparacione->articulo->cliente->apellido }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Teléfono:</span>
                        <div class="info-value">{{ $reparacione->articulo->cliente->telefono ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <div class="info-value">{{ $reparacione->articulo->cliente->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dirección:</span>
                        <div class="info-value">{{ $reparacione->articulo->cliente->direccion ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            {{-- DATOS DEL EQUIPO --}}
            <div class="section">
                <div class="section-title">🔧 DATOS DEL EQUIPO</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Tipo:</span>
                        <div class="info-value">{{ $reparacione->articulo->tipo }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marca:</span>
                        <div class="info-value">{{ $reparacione->articulo->marca }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modelo:</span>
                        <div class="info-value">{{ $reparacione->articulo->modelo }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Serial:</span>
                        <div class="info-value">{{ $reparacione->articulo->serial ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            {{-- INFORME DE REPARACIÓN --}}
            <div class="section">
                <div class="section-title">🛠️ INFORME DE REPARACIÓN</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Problema Reportado:</span>
                        <div class="info-value">{{ $reparacione->articulo->problema_descripcion }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Diagnóstico:</span>
                        <div class="info-value">{{ $reparacione->diagnostico ?? 'No registrado' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Solución Aplicada:</span>
                        <div class="info-value">{{ $reparacione->solucion ?? 'No registrada' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Técnico Responsable:</span>
                        <div class="info-value">{{ $reparacione->tecnico->nombre }} {{ $reparacione->tecnico->apellido }}</div>
                    </div>
                </div>
            </div>

            {{-- REPUESTOS UTILIZADOS --}}
            <div class="section">
                <div class="section-title">🔩 REPUESTOS UTILIZADOS</div>
                @if($reparacione->repuestos->count() > 0)
                <table class="table">
                    <thead>
                        
                            <th>Código</th>
                            <th>Repuesto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </thead>
                    <tbody>
                        @foreach($reparacione->repuestos as $repuesto)
                        <tr>
                            <td>{{ $repuesto->codigo }} 
                            <td>{{ $repuesto->nombre }} 
                            <td>{{ $repuesto->pivot->cantidad }} 
                            <td>Bs {{ number_format($repuesto->pivot->precio_unitario, 2) }} 
                            <td>Bs {{ number_format($repuesto->pivot->precio_unitario * $repuesto->pivot->cantidad, 2) }} 
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="padding: 0 15px; color: #666;">No se utilizaron repuestos en esta reparación.</p>
                @endif
            </div>

            {{-- TIEMPOS Y COSTOS --}}
            <div class="section">
                <div class="section-title">⏱️ TIEMPOS Y COSTOS</div>
                @php
                    $totalRepuestos = 0;
                    foreach ($reparacione->repuestos as $repuesto) {
                        $totalRepuestos += $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario;
                    }
                    $totalReparacion = ($reparacione->costo_reparacion ?? 0) + $totalRepuestos;
                @endphp
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Fecha de Ingreso:</span>
                        <div class="info-value">{{ \Carbon\Carbon::parse($reparacione->fecha_asignacion)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Entrega:</span>
                        <div class="info-value">{{ $reparacione->fecha_entrega ? \Carbon\Carbon::parse($reparacione->fecha_entrega)->format('d/m/Y') : 'Pendiente' }}</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tiempo Estimado:</span>
                        <div class="info-value">{{ $reparacione->tiempo_estimado_horas ?? 'N/A' }} horas</div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Costo Mano de Obra:</span>
                        <div class="info-value">Bs {{ number_format($reparacione->costo_reparacion ?? 0, 2) }}</div>
                    </div>
                    @if($totalRepuestos > 0)
                    <div class="info-item">
                        <span class="info-label">Costo Repuestos:</span>
                        <div class="info-value">Bs {{ number_format($totalRepuestos, 2) }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Total Reparación:</span>
                        <div class="info-value fw-bold text-success">Bs {{ number_format($totalReparacion, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- FIRMAS --}}
            <div class="signature">
                <div class="signature-line">
                    <hr>
                    <p>Firma del Cliente</p>
                </div>
                <div class="signature-line">
                    <hr>
                    <p>Firma del Técnico</p>
                </div>
                <div class="signature-line">
                    <hr>
                    <p>Sello del Taller</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>ElectroBol - Taller de Reparación de Electrodomésticos</p>
            <p>Este documento es un comprobante de la reparación realizada. Conserve para futuras referencias.</p>
        </div>
    </div>
</body>
</html>