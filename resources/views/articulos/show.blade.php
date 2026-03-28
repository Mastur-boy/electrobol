@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-device-ssd me-2"></i>Detalle del Artículo
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articulos.index') }}">Artículos</a></li>
                    <li class="breadcrumb-item active">{{ $articulo->tipo }} {{ $articulo->marca }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            {{-- Información del Artículo --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle me-2"></i>Información del Equipo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-tv fs-1 text-secondary"></i>
                        <h4 class="mt-2">{{ $articulo->tipo }} {{ $articulo->marca }} {{ $articulo->modelo }}</h4>
                        @if($articulo->tiene_garantia_vigente)
                            <span class="badge bg-success">Garantía Vigente</span>
                        @else
                            <span class="badge bg-secondary">Sin Garantía</span>
                        @endif
                    </div>
                    <hr>
                    <p><strong><i class="bi bi-upc-scan me-2"></i>Serial:</strong> {{ $articulo->serial ?? 'No registrado' }}</p>
                    <p><strong><i class="bi bi-calendar me-2"></i>Fecha Registro:</strong> {{ $articulo->created_at->format('d/m/Y') }}</p>
                    @if($articulo->fecha_garantia)
                        <p><strong><i class="bi bi-calendar-check me-2"></i>Vencimiento Garantía:</strong> {{ \Carbon\Carbon::parse($articulo->fecha_garantia)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>

            {{-- Información del Cliente --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person me-2"></i>Información del Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong><i class="bi bi-person-circle me-2"></i>Nombre:</strong> {{ $articulo->cliente->nombre }} {{ $articulo->cliente->apellido }}</p>
                    <p><strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong> {{ $articulo->cliente->telefono ?? 'N/A' }}</p>
                    <p><strong><i class="bi bi-envelope me-2"></i>Email:</strong> {{ $articulo->cliente->email ?? 'N/A' }}</p>
                    <p><strong><i class="bi bi-geo-alt me-2"></i>Dirección:</strong> {{ $articulo->cliente->direccion ?? 'N/A' }}</p>
                    <a href="{{ route('clientes.show', $articulo->cliente) }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-eye me-1"></i>Ver Cliente
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            {{-- Problema Reportado --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Problema Reportado
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $articulo->problema_descripcion }}</p>
                </div>
            </div>

            {{-- Estado de Reparación --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-wrench me-2"></i>Estado de Reparación
                    </h5>
                </div>
                <div class="card-body">
                    @if($articulo->reparacion)
                        <div class="alert alert-{{ $articulo->reparacion->estado == 'entregado' ? 'success' : ($articulo->reparacion->estado == 'reparacion' ? 'info' : 'warning') }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Estado Actual:</strong>
                                    <span class="status-badge status-{{ $articulo->reparacion->estado }} ms-2">
                                        @switch($articulo->reparacion->estado)
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
                                <a href="{{ route('reparaciones.show', $articulo->reparacion) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i>Ver Reparación
                                </a>
                            </div>
                        </div>

                        @if($articulo->reparacion->diagnostico)
                            <div class="mt-3">
                                <strong><i class="bi bi-chat-dots me-2"></i>Diagnóstico:</strong>
                                <p class="mt-2 mb-0 text-muted">{{ $articulo->reparacion->diagnostico }}</p>
                            </div>
                        @endif

                        @if($articulo->reparacion->tiempo_estimado_horas)
                            <div class="mt-3">
                                <strong><i class="bi bi-clock me-2"></i>Tiempo Estimado:</strong>
                                <p class="mt-1">{{ $articulo->reparacion->tiempo_estimado_horas }} horas</p>
                            </div>
                        @endif

                        @if($articulo->reparacion->costo_reparacion)
                            <div class="mt-3">
                                <strong><i class="bi bi-cash-stack me-2"></i>Costo de Reparación:</strong>
                                <p class="mt-1 text-success fw-bold">Bs {{ number_format($articulo->reparacion->costo_reparacion, 2) }}</p>
                            </div>
                        @endif

                        @if($articulo->reparacion->tecnico)
                            <div class="mt-3">
                                <strong><i class="bi bi-person-badge me-2"></i>Técnico Asignado:</strong>
                                <p class="mt-1">{{ $articulo->reparacion->tecnico->nombre }} {{ $articulo->reparacion->tecnico->apellido }}</p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Este artículo aún no ha sido asignado a un técnico.</strong>
                            <p class="mb-0 mt-2">Para iniciar el proceso de reparación, debes asignarlo a un técnico.</p>
                        </div>

                        {{-- Formulario de Asignación --}}
                        <form action="{{ route('articulos.asignar-tecnico', $articulo) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Seleccionar Técnico</label>
                                    <select name="tecnico_id" class="form-select" required>
                                        <option value="">-- Seleccione un técnico --</option>
                                        @foreach($tecnicos as $tecnico)
                                            <option value="{{ $tecnico->id }}">
                                                {{ $tecnico->nombre }} {{ $tecnico->apellido }} - {{ $tecnico->especialidad ?? 'Sin especialidad' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-custom w-100">
                                        <i class="bi bi-check-circle me-2"></i>Asignar Técnico
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Repuestos Utilizados --}}
            @if($articulo->reparacion && $articulo->reparacion->repuestos->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-box-seam me-2"></i>Repuestos Utilizados
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($articulo->reparacion->repuestos as $repuesto)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $repuesto->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">Código: {{ $repuesto->codigo }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-info">Cantidad: {{ $repuesto->pivot->cantidad }}</span>
                                        <br>
                                        <small class="text-muted">Bs {{ number_format($repuesto->pivot->precio_unitario, 2) }} c/u</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Botones de acción --}}
            <div class="mt-4 text-end">
                @if(!$articulo->reparacion)
                    <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Editar Artículo
                    </a>
                @endif
                <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
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
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-evaluacion { background-color: #ffc107; color: #000; }
    .status-reparacion { background-color: #17a2b8; color: #fff; }
    .status-entregado { background-color: #28a745; color: #fff; }
</style>
@endpush