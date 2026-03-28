@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-search me-2"></i>Buscar Equipos por Técnico
            </h2>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.buscar-tecnico') }}" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Seleccionar Técnico</label>
                    <select name="tecnico_id" class="form-select" required>
                        <option value="">-- Seleccione un técnico --</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" {{ request('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->nombre_completo }} - {{ $tecnico->especialidad ?? 'Sin especialidad' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-custom w-100">
                        <i class="bi bi-search me-2"></i>Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($reparaciones->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-list-check me-2"></i>Equipos Asignados
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
                                <th>Estado</th>
                                <th>Fecha Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reparaciones as $reparacion)
                                <tr class="{{ !$reparacion->articulo ? 'table-warning' : '' }}">
                                    <td class="fw-bold">#{{ $reparacion->id }}</td>
                                    
                                    {{-- Columna Cliente --}}
                                    <td>
                                        @if($reparacion->articulo && $reparacion->articulo->cliente)
                                            {{ $reparacion->articulo->cliente->nombre_completo }}
                                            <br>
                                            <small class="text-muted">{{ $reparacion->articulo->cliente->telefono ?? 'Sin teléfono' }}</small>
                                        @elseif($reparacion->articulo && !$reparacion->articulo->cliente)
                                            <span class="text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                Cliente no disponible
                                            </span>
                                            <br>
                                            <small class="text-muted">ID Artículo: {{ $reparacion->articulo_id }}</small>
                                        @else
                                            <span class="text-danger">
                                                <i class="bi bi-database-slash me-1"></i>
                                                Artículo no disponible
                                            </span>
                                            <br>
                                            <small class="text-muted">ID Artículo: {{ $reparacion->articulo_id ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    
                                    {{-- Columna Artículo --}}
                                    <td>
                                        @if($reparacion->articulo)
                                            <strong>{{ $reparacion->articulo->tipo }}</strong>
                                            <br>
                                            <small>{{ $reparacion->articulo->marca }} {{ $reparacion->articulo->modelo }}</small>
                                            @if($reparacion->articulo->serial)
                                                <br>
                                                <small class="text-muted">Serial: {{ $reparacion->articulo->serial }}</small>
                                            @endif
                                        @else
                                            <span class="text-danger">
                                                <i class="bi bi-database-slash me-1"></i>
                                                Artículo eliminado
                                            </span>
                                            <br>
                                            <small class="text-muted">ID: {{ $reparacion->articulo_id ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    
                                    {{-- Columna Estado --}}
                                    <td>
                                        <span class="status-badge status-{{ $reparacion->estado }}">
                                            @switch($reparacion->estado)
                                                @case('evaluacion')
                                                    <i class="bi bi-clipboard-check me-1"></i>Evaluación
                                                    @break
                                                @case('reparacion')
                                                    <i class="bi bi-tools me-1"></i>Reparación
                                                    @break
                                                @case('entregado')
                                                    <i class="bi bi-check-circle me-1"></i>Entregado
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    
                                    {{-- Columna Fecha --}}
                                    <td>{{ \Carbon\Carbon::parse($reparacion->fecha_asignacion)->format('d/m/Y') }}</td>
                                    
                                    {{-- Columna Acciones --}}
                                    <td>
                                        @if($reparacion->articulo)
                                            <a href="{{ route('reparaciones.show', $reparacion) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled title="Artículo no disponible">
                                                <i class="bi bi-eye-slash"></i> No disponible
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->has('tecnico_id'))
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No se encontraron reparaciones asignadas a este técnico.
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .btn-custom {
        background-color: #77dd77;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #66cc66;
        color: white;
        transform: translateY(-1px);
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
    .table-warning {
        background-color: #fff3cd !important;
    }
    .text-warning {
        color: #856404 !important;
    }
    .text-danger {
        color: #dc3545 !important;
    }
</style>
@endpush