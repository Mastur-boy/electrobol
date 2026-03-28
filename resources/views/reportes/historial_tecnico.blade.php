@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-clock-history me-2"></i>Historial de Actualizaciones por Técnico
            </h2>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.historial-tecnico') }}" class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Seleccionar Técnico</label>
                    <select name="tecnico_id" class="form-select" required>
                        <option value="">-- Seleccione un técnico --</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" {{ request('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                {{ $tecnico->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-custom w-100">
                        <i class="bi bi-search me-2"></i>Ver Historial
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($historial->count() > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Registro de Actividades
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="timeline">
                    @foreach($historial as $registro)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <span class="badge bg-info me-2">
                                                {{ ucfirst($registro->accion) }}
                                            </span>
                                            Reparación #{{ $registro->reparacion->id }}
                                        </h6>
                                        <p class="mb-1">{{ $registro->detalle }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-device-ssd me-1"></i>
                                            {{ $registro->reparacion->articulo->tipo }} 
                                            {{ $registro->reparacion->articulo->marca }} 
                                            {{ $registro->reparacion->articulo->modelo }}
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif(request()->has('tecnico_id'))
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No se encontraron registros de actividad para este técnico.
        </div>
    @endif
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding: 1rem 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 2rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        padding-left: 4rem;
        margin-bottom: 1.5rem;
    }
    .timeline-marker {
        position: absolute;
        left: 1.5rem;
        top: 0;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #77dd77;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #77dd77;
    }
    .timeline-content {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endpush
@endsection