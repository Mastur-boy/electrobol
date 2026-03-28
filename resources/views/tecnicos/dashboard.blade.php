@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-person-badge me-2"></i>Panel del Técnico
            </h2>
            <p class="text-muted">Bienvenido, {{ $tecnico->nombre_completo }} - {{ $tecnico->especialidad ?? 'Sin especialidad' }}</p>
        </div>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Total Asignadas</h6>
                            <h2 class="mb-0">{{ $totalAsignadas }}</h2>
                        </div>
                        <i class="bi bi-clipboard-check fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-dark-50">En Proceso</h6>
                            <h2 class="mb-0">{{ $totalEnProceso }}</h2>
                        </div>
                        <i class="bi bi-gear fs-1 text-dark-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50">Completadas</h6>
                            <h2 class="mb-0">{{ $totalCompletadas }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reparaciones en Evaluación --}}
    @if($enEvaluacion->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-clipboard2-check me-2"></i>Pendientes de Evaluación
                <span class="badge bg-warning ms-2">{{ $enEvaluacion->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Problema</th>
                            <th>Fecha Asignación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enEvaluacion as $reparacion)
                        <tr>
                            <td class="fw-bold">#{{ $reparacion->id }}</td>
                            <td>
                                @if($reparacion->articulo && $reparacion->articulo->cliente)
                                    {{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}
                                @else
                                    <span class="text-muted">Cliente no disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($reparacion->articulo)
                                    <strong>{{ $reparacion->articulo->tipo }}</strong><br>
                                    <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                                @else
                                    <span class="text-muted">Artículo no disponible</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    @if($reparacion->articulo)
                                        {{ Str::limit($reparacion->articulo->problema_descripcion, 50) }}
                                    @else
                                        Problema no disponible
                                    @endif
                                </small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reparacion->fecha_asignacion)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-clipboard-check"></i> Evaluar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Reparaciones en Proceso --}}
    @if($enReparacion->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-tools me-2"></i>Reparaciones en Curso
                <span class="badge bg-info ms-2">{{ $enReparacion->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Diagnóstico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enReparacion as $reparacion)
                        <tr>
                            <td class="fw-bold">#{{ $reparacion->id }}</td>
                            <td>
                                @if($reparacion->articulo && $reparacion->articulo->cliente)
                                    {{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}
                                @else
                                    <span class="text-muted">Cliente no disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($reparacion->articulo)
                                    <strong>{{ $reparacion->articulo->tipo }}</strong><br>
                                    <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                                @else
                                    <span class="text-muted">Artículo no disponible</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($reparacion->diagnostico, 50) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-arrow-up-circle"></i> Registrar Avance
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Reparaciones Completadas --}}
    @if($entregadas->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-check-circle me-2"></i>Reparaciones Completadas
                <span class="badge bg-success ms-2">{{ $entregadas->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Artículo</th>
                            <th>Fecha Finalización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entregadas as $reparacion)
                        <tr>
                            <td class="fw-bold">#{{ $reparacion->id }}</td>
                            <td>
                                @if($reparacion->articulo && $reparacion->articulo->cliente)
                                    {{ $reparacion->articulo->cliente->nombre }} {{ $reparacion->articulo->cliente->apellido }}
                                @else
                                    <span class="text-muted">Cliente no disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($reparacion->articulo)
                                    <strong>{{ $reparacion->articulo->tipo }}</strong><br>
                                    <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                                @else
                                    <span class="text-muted">Artículo no disponible</span>
                                @endif
                            </td>
                            <td>{{ $reparacion->fecha_fin_reparacion ? \Carbon\Carbon::parse($reparacion->fecha_fin_reparacion)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    @if($enEvaluacion->count() == 0 && $enReparacion->count() == 0 && $entregadas->count() == 0)
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
        <h5>No tienes reparaciones asignadas</h5>
        <p class="mb-0">Cuando te asignen un equipo, aparecerá aquí.</p>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
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