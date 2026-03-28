@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">
                    <i class="bi bi-wrench me-2"></i>Reparación #{{ $reparacione->id }}
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reparaciones.index') }}">Reparaciones</a></li>
                        <li class="breadcrumb-item active">Detalle</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                {{-- Información del Artículo --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-device-ssd me-2"></i>Información del Artículo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Cliente:</strong>
                                    @if ($reparacione->articulo && $reparacione->articulo->cliente)
                                        {{ $reparacione->articulo->cliente->nombre_completo }}
                                    @else
                                        <span class="text-danger">Cliente no disponible</span>
                                    @endif
                                </p>
                                <p><strong>Teléfono:</strong>
                                    @if ($reparacione->articulo && $reparacione->articulo->cliente)
                                        {{ $reparacione->articulo->cliente->telefono ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Email:</strong>
                                    @if ($reparacione->articulo && $reparacione->articulo->cliente)
                                        {{ $reparacione->articulo->cliente->email ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Artículo:</strong>
                                    @if ($reparacione->articulo)
                                        {{ $reparacione->articulo->tipo }} - {{ $reparacione->articulo->marca }}
                                        {{ $reparacione->articulo->modelo }}
                                    @else
                                        <span class="text-danger">Artículo no disponible</span>
                                    @endif
                                </p>
                                <p><strong>Serial:</strong>
                                    @if ($reparacione->articulo)
                                        {{ $reparacione->articulo->serial ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Garantía:</strong>
                                    @if ($reparacione->articulo && $reparacione->articulo->tieneGarantiaVigente())
                                        <span class="badge bg-success">Vigente</span>
                                    @else
                                        <span class="badge bg-secondary">No vigente</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p><strong>Problema reportado:</strong></p>
                            <p class="text-muted">
                                @if ($reparacione->articulo)
                                    {{ $reparacione->articulo->problema_descripcion }}
                                @else
                                    Problema no disponible
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Estado y Progreso --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-graph-up me-2"></i>Estado de la Reparación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <span class="status-badge status-{{ $reparacione->estado }}">
                                @switch($reparacione->estado)
                                    @case('evaluacion')
                                        <i class="bi bi-clipboard-check me-1"></i>En Evaluación
                                    @break

                                    @case('reparacion')
                                        <i class="bi bi-tools me-1"></i>En Reparación
                                    @break

                                    @case('entregado')
                                        <i class="bi bi-check-circle me-1"></i>Entregado
                                    @break
                                @endswitch
                            </span>
                        </div>

                        @if ($reparacione->diagnostico)
                            <div class="alert alert-info">
                                <strong><i class="bi bi-chat-dots me-2"></i>Diagnóstico:</strong>
                                <p class="mt-2 mb-0">{{ $reparacione->diagnostico }}</p>
                            </div>
                        @endif

                        @if ($reparacione->tiempo_estimado_horas)
                            <p><strong>Tiempo estimado:</strong> {{ $reparacione->tiempo_estimado_horas }} horas</p>
                        @endif

                        @if ($reparacione->costo_reparacion)
                            <p><strong>Costo de reparación:</strong> Bs
                                {{ number_format($reparacione->costo_reparacion, 2) }}</p>
                        @endif

                        @if ($reparacione->solucion)
                            <div class="alert alert-success mt-3">
                                <strong><i class="bi bi-check-circle me-2"></i>Solución Aplicada:</strong>
                                <p class="mt-2 mb-0">{{ $reparacione->solucion }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Historial de Actualizaciones --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2"></i>Historial de Actualizaciones
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($reparacione->historial as $registro)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ ucfirst($registro->accion) }}</strong>
                                            <p class="mb-0 text-muted small">{{ $registro->detalle }}</p>
                                        </div>
                                        <small class="text-muted">{{ $registro->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No hay registros de historial.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {{-- Acciones según estado --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-gear me-2"></i>Acciones
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($reparacione->estado == 'evaluacion' && $reparacione->articulo && !$reparacione->articulo->evaluacion_realizada)
                            {{-- Formulario de Evaluación --}}
                            <form action="{{ route('reparaciones.evaluar', $reparacione) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Diagnóstico</label>
                                    <textarea name="diagnostico" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tiempo estimado (horas)</label>
                                    <input type="number" name="tiempo_estimado_horas" class="form-control" step="0.5"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Costo de reparación (Bs)</label>
                                    <input type="number" name="costo_reparacion" class="form-control" step="0.01"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-custom w-100">
                                    <i class="bi bi-save me-2"></i>Registrar Evaluación
                                </button>
                            </form>
                        @elseif(
                            $reparacione->estado == 'evaluacion' &&
                                $reparacione->articulo &&
                                $reparacione->articulo->evaluacion_realizada &&
                                is_null($reparacione->articulo->reparacion_aceptada))
                            {{-- Esperando aprobación del cliente --}}
                            <div class="alert alert-warning">
                                <i class="bi bi-hourglass-split me-2"></i>
                                Esperando aprobación del cliente para iniciar la reparación.
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#aceptarModal">
                                    <i class="bi bi-check-circle me-2"></i>Aceptar Reparación (Cliente)
                                </button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rechazarModal">
                                    <i class="bi bi-x-circle me-2"></i>Rechazar Reparación (Cliente)
                                </button>
                            </div>

                            {{-- Modales --}}
                            <div class="modal fade" id="aceptarModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar Aceptación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Confirmar que el cliente acepta la reparación?</p>
                                            <p><strong>Costo:</strong> Bs
                                                {{ number_format($reparacione->costo_reparacion, 2) }}</p>
                                            <p><strong>Tiempo estimado:</strong> {{ $reparacione->tiempo_estimado_horas }}
                                                horas</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('reparaciones.aceptar', $reparacione) }}"
                                                method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success">Aceptar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="rechazarModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar Rechazo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Confirmar que el cliente rechaza la reparación?</p>
                                            <p class="text-danger">Se generará un cobro de Bs 1008 por evaluación.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('reparaciones.rechazar', $reparacione) }}"
                                                method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Rechazar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($reparacione->estado == 'reparacion')
                            {{-- Durante la reparación --}}
                            <form action="{{ route('reparaciones.avance', $reparacione) }}" method="POST"
                                class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Registrar Avance</label>
                                    <textarea name="detalle_avance" class="form-control" rows="3" placeholder="Describa el avance realizado..."
                                        required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-arrow-up-circle me-2"></i>Registrar Avance
                                </button>
                            </form>

                            <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#esperarModal">
                                <i class="bi bi-clock me-2"></i>Esperando Repuesto
                            </button>

                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#finalizarModal">
                                <i class="bi bi-check-circle me-2"></i>Finalizar Reparación
                            </button>

                            {{-- Modal Esperar Repuesto --}}
                            <div class="modal fade" id="esperarModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Esperando Repuesto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('reparaciones.esperar', $reparacione) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Repuesto necesario</label>
                                                    <select name="repuesto_id" class="form-select" required>
                                                        <option value="">Seleccione un repuesto</option>
                                                        @foreach ($repuestosDisponibles as $repuesto)
                                                            <option value="{{ $repuesto->id }}">{{ $repuesto->nombre }}
                                                                (Stock: {{ $repuesto->stock_actual }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Motivo / Detalle</label>
                                                    <textarea name="motivo" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-warning">Registrar Espera</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Finalizar con campo Solución --}}
                            <div class="modal fade" id="finalizarModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Finalizar Reparación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('reparaciones.finalizar', $reparacione) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Solución Aplicada *</label>
                                                    <textarea name="solucion" class="form-control" rows="4"
                                                        placeholder="Describa detalladamente la solución aplicada al equipo..." required></textarea>
                                                    <small class="text-muted">Ej: Se reemplazó la placa principal, se
                                                        realizaron pruebas de funcionamiento...</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success">Finalizar
                                                    Reparación</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif($reparacione->estado == 'entregado')
                            {{-- Reparación finalizada pero no entregada --}}
                            @if (!$reparacione->fecha_entrega)
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    La reparación está lista para entregar al cliente.
                                </div>
                                <form action="{{ route('reparaciones.entregar', $reparacione) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Método de Pago</label>
                                        <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                                            <option value="efectivo">💵 Efectivo</option>
                                            <option value="tarjeta">💳 Tarjeta (POS)</option>
                                            <option value="transferencia">🏦 Transferencia Bancaria</option>
                                            <option value="qr">📱 Pago QR (Yape/Yasta/QR Simple)</option>
                                        </select>
                                    </div>

                                    {{-- Información adicional según método de pago --}}
                                    <div id="info_efectivo" class="payment-info" style="display: none;">
                                        <div class="alert alert-secondary mt-2">
                                            <i class="bi bi-cash-stack me-2"></i>
                                            <strong>Efectivo:</strong> Prepare el vuelto si es necesario.
                                        </div>
                                    </div>

                                    <div id="info_tarjeta" class="payment-info" style="display: none;">
                                        <div class="alert alert-secondary mt-2">
                                            <i class="bi bi-credit-card me-2"></i>
                                            <strong>Tarjeta:</strong> Acérquese al POS para realizar el pago.
                                        </div>
                                    </div>

                                    <div id="info_transferencia" class="payment-info" style="display: none;">
                                        <div class="alert alert-secondary mt-2">
                                            <i class="bi bi-bank me-2"></i>
                                            <strong>Transferencia Bancaria:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><strong>Banco:</strong> Banco Mercantil Santa Cruz</li>
                                                <li><strong>Cuenta:</strong> 12345678901234</li>
                                                <li><strong>CCI:</strong> 120-12345678901234-56</li>
                                                <li><strong>Titular:</strong> ELECTROBOL S.R.L.</li>
                                            </ul>
                                            <small class="text-muted mt-2 d-block">El CCI es un código único de 20 dígitos
                                                que identifica tu cuenta a nivel nacional.</small>
                                        </div>
                                    </div>

                                    <div id="info_qr" class="payment-info" style="display: none;">
                                        <div class="alert alert-secondary text-center mt-2">
                                            <div class="mb-3">
                                                <i class="bi bi-qr-code-scan fs-1 text-success"></i>
                                                <i class="bi bi-phone fs-1 text-primary ms-2"></i>
                                                <i class="bi bi-wallet2 fs-1 text-warning ms-2"></i>
                                            </div>
                                            <strong class="fs-5">Pago con Código QR</strong>
                                            <p class="mb-2 text-muted small">Escanee con su aplicación de pagos</p>

                                            {{-- QR Decorativo Mejorado --}}
                                            <div class="bg-white p-3 rounded shadow-sm d-inline-block mt-2"
                                                style="border: 1px solid #e0e0e0;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="140" height="140"
                                                    viewBox="0 0 100 100">
                                                    <rect width="100" height="100" fill="white" />
                                                    <rect x="10" y="10" width="8" height="8" fill="black" />
                                                    <rect x="10" y="18" width="8" height="8" fill="black" />
                                                    <rect x="18" y="10" width="8" height="8" fill="black" />
                                                    <rect x="26" y="10" width="8" height="8" fill="black" />
                                                    <rect x="10" y="26" width="8" height="8" fill="black" />
                                                    <rect x="18" y="18" width="8" height="8" fill="black" />
                                                    <rect x="82" y="10" width="8" height="8" fill="black" />
                                                    <rect x="74" y="10" width="8" height="8" fill="black" />
                                                    <rect x="66" y="10" width="8" height="8" fill="black" />
                                                    <rect x="82" y="18" width="8" height="8" fill="black" />
                                                    <rect x="82" y="26" width="8" height="8" fill="black" />
                                                    <rect x="74" y="18" width="8" height="8" fill="black" />
                                                    <rect x="10" y="82" width="8" height="8" fill="black" />
                                                    <rect x="10" y="74" width="8" height="8" fill="black" />
                                                    <rect x="10" y="66" width="8" height="8" fill="black" />
                                                    <rect x="18" y="82" width="8" height="8" fill="black" />
                                                    <rect x="26" y="82" width="8" height="8" fill="black" />
                                                    <rect x="18" y="74" width="8" height="8" fill="black" />
                                                    <rect x="40" y="40" width="20" height="20" fill="black" />
                                                    <rect x="45" y="45" width="10" height="10" fill="white" />
                                                    <rect x="55" y="30" width="4" height="4" fill="black" />
                                                    <rect x="30" y="55" width="4" height="4" fill="black" />
                                                    <rect x="65" y="65" width="4" height="4" fill="black" />
                                                    <rect x="35" y="70" width="4" height="4" fill="black" />
                                                    <rect x="70" y="35" width="4" height="4" fill="black" />
                                                </svg>
                                            </div>
                                            
                                            {{-- 👈 CÁLCULO CORRECTO DEL TOTAL CON REPUESTOS --}}
                                            @php
                                                $totalConRepuestos = $reparacione->costo_reparacion ?? 0;
                                                foreach ($reparacione->repuestos as $repuesto) {
                                                    $totalConRepuestos += $repuesto->pivot->cantidad * $repuesto->pivot->precio_unitario;
                                                }
                                            @endphp
                                            
                                            <div class="mt-3">
                                                <p class="mb-0"><strong>Monto a pagar:</strong></p>
                                                <p class="fs-3 fw-bold text-success mb-0">Bs {{ number_format($totalConRepuestos, 2) }}</p>
                                                <small class="text-muted">Reparación #{{ $reparacione->id }}</small>
                                                @if($reparacione->repuestos->count() > 0)
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-box-seam"></i> Incluye {{ $reparacione->repuestos->count() }} repuesto(s)
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            {{-- Aplicaciones compatibles --}}
                                            <div class="mt-3 d-flex justify-content-center gap-3">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-phone"></i> Yape
                                                </span>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-phone"></i> Yasta
                                                </span>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-bank"></i> QR Simple
                                                </span>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-wallet2"></i> Pago QR
                                                </span>
                                            </div>
                                            
                                            <div class="mt-3 small text-muted">
                                                <i class="bi bi-info-circle"></i>
                                                Al escanear el código, verifique que el monto sea el correcto.
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 mt-3">
                                        <i class="bi bi-cash-stack me-2"></i>Entregar y Registrar Pago
                                    </button>
                                </form>

                                <script>
                                    document.getElementById('metodo_pago').addEventListener('change', function() {
                                        document.querySelectorAll('.payment-info').forEach(el => el.style.display = 'none');
                                        const infoId = 'info_' + this.value;
                                        const infoDiv = document.getElementById(infoId);
                                        if (infoDiv) infoDiv.style.display = 'block';
                                    });
                                    document.getElementById('metodo_pago').dispatchEvent(new Event('change'));
                                </script>
                            @else
                                {{-- Reparación ya entregada --}}
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Equipo entregado el
                                    {{ \Carbon\Carbon::parse($reparacione->fecha_entrega)->format('d/m/Y') }}
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('reparaciones.informe', $reparacione) }}" class="btn btn-info"
                                        target="_blank">
                                        <i class="bi bi-file-text me-2"></i>Ver Informe Técnico
                                    </a>
                                    <a href="{{ route('reparaciones.factura', $reparacione) }}" class="btn btn-custom"
                                        target="_blank">
                                        <i class="bi bi-receipt me-2"></i>Ver Factura
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Repuestos Utilizados --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-box-seam me-2"></i>Repuestos Utilizados
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($reparacione->repuestos as $repuesto)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>{{ $repuesto->nombre }}</strong>
                                            <small class="text-muted d-block">Cantidad:
                                                {{ $repuesto->pivot->cantidad }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold">Bs
                                                {{ number_format($repuesto->pivot->precio_unitario * $repuesto->pivot->cantidad, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No se han utilizado repuestos.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-custom {
            background-color: #77dd77;
            border: none;
            color: white;
        }

        .btn-custom:hover {
            background-color: #66cc66;
            color: white;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-evaluacion {
            background-color: #ffc107;
            color: #000;
        }

        .status-reparacion {
            background-color: #17a2b8;
            color: #fff;
        }

        .status-entregado {
            background-color: #28a745;
            color: #fff;
        }
    </style>
@endpush