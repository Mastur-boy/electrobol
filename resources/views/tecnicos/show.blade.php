@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-person-badge me-2"></i>Detalle del Técnico
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tecnicos.index') }}">Técnicos</a></li>
                    <li class="breadcrumb-item active">{{ $tecnico->nombre }} {{ $tecnico->apellido }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        {{-- Columna Izquierda: Información Personal --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle me-2"></i>Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                        <h4 class="mt-2">{{ $tecnico->nombre }} {{ $tecnico->apellido }}</h4>
                        @if($tecnico->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </div>
                    <hr>
                    
                    {{-- Datos del usuario vinculado --}}
                    @if($tecnico->user)
                        <p><strong><i class="bi bi-card-text me-2"></i>CI:</strong> {{ $tecnico->user->ci ?? 'No registrado' }}</p>
                        <p><strong><i class="bi bi-envelope me-2"></i>Email:</strong> {{ $tecnico->user->email }}</p>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Este técnico no tiene una cuenta de usuario vinculada.
                            <a href="{{ route('tecnicos.vincular-form', $tecnico) }}">Vincular ahora</a>
                        </div>
                    @endif
                    
                    <p><strong><i class="bi bi-tools me-2"></i>Especialidad:</strong> {{ $tecnico->especialidad ?? 'Sin especialidad' }}</p>
                    <p><strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong> {{ $tecnico->telefono ?? 'N/A' }}</p>
                    <p><strong><i class="bi bi-calendar me-2"></i>Fecha Registro:</strong> {{ $tecnico->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Reparaciones --}}
        <div class="col-md-8">
            
            {{-- Reparaciones en Evaluación --}}
            @if($reparacionesEvaluacion->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clipboard-check me-2"></i>En Evaluación
                        <span class="badge bg-warning ms-2">{{ $reparacionesEvaluacion->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <thead>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Artículo</th>
                                    <th>Fecha Asignación</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody>
                                @foreach($reparacionesEvaluacion as $rep)
                                <tr>
                                    <td class="fw-bold">#{{ $rep->id }}</td>
                                    <td>{{ $rep->articulo->cliente->nombre_completo }}</td>
                                    <td>
                                        <strong>{{ $rep->articulo->tipo }}</strong><br>
                                        <small>{{ $rep->articulo->marca }} {{ $rep->articulo->modelo }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($rep->fecha_asignacion)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('reparaciones.show', $rep) }}" class="btn btn-sm btn-primary">
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
            @if($reparacionesReparacion->count() > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tools me-2"></i>En Reparación
                        <span class="badge bg-info ms-2">{{ $reparacionesReparacion->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Artículo</th>
                                    <th>Diagnóstico</th>
                                    <th>Fecha Inicio</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody>
                                @foreach($reparacionesReparacion as $rep)
                                <tr>
                                    <td class="fw-bold">#{{ $rep->id }}</td>
                                    <td>{{ $rep->articulo->cliente->nombre_completo }}</td>
                                    <td>
                                        <strong>{{ $rep->articulo->tipo }}</strong><br>
                                        <small>{{ $rep->articulo->marca }} {{ $rep->articulo->modelo }}</small>
                                    </td>
                                    <td>{{ Str::limit($rep->diagnostico, 50) ?? 'N/A' }}</td>
                                    <td>{{ $rep->fecha_inicio_reparacion ? \Carbon\Carbon::parse($rep->fecha_inicio_reparacion)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('reparaciones.show', $rep) }}" class="btn btn-sm btn-info">
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

            {{-- Reparaciones Entregadas --}}
            @if($reparacionesEntregado->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-check-circle me-2"></i>Entregadas
                        <span class="badge bg-success ms-2">{{ $reparacionesEntregado->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Artículo</th>
                                    <th>Fecha Finalización</th>
                                    <th>Acciones</th>
                                </thead>
                            <tbody>
                                @foreach($reparacionesEntregado as $rep)
                                <tr>
                                    <td class="fw-bold">#{{ $rep->id }}</td>
                                    <td>{{ $rep->articulo->cliente->nombre_completo }}</td>
                                    <td>
                                        <strong>{{ $rep->articulo->tipo }}</strong><br>
                                        <small>{{ $rep->articulo->marca }} {{ $rep->articulo->modelo }}</small>
                                    </td>
                                    <td>{{ $rep->fecha_fin_reparacion ? \Carbon\Carbon::parse($rep->fecha_fin_reparacion)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('reparaciones.show', $rep) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> Ver Detalle
                                        </a>
                                        <a href="{{ route('reparaciones.factura', $rep) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                            <i class="bi bi-receipt"></i> Factura
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

            @if($reparacionesEvaluacion->count() == 0 && $reparacionesReparacion->count() == 0 && $reparacionesEntregado->count() == 0)
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <h5>No hay reparaciones asignadas a este técnico</h5>
                <p class="mb-0">Cuando se le asignen reparaciones, aparecerán aquí.</p>
            </div>
            @endif

            {{-- Botones de acción --}}
            <div class="mt-4 text-end">
                <a href="{{ route('tecnicos.edit', $tecnico) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="{{ route('tecnicos.reporte', $tecnico) }}" class="btn btn-info">
                    <i class="bi bi-file-text me-2"></i>Reporte Completo
                </a>
                <a href="{{ route('tecnicos.historial', $tecnico) }}" class="btn btn-secondary">
                    <i class="bi bi-clock-history me-2"></i>Ver Historial
                </a>
            </div>
        </div>
    </div>
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